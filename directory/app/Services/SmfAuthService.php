<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use Illuminate\Support\Str;

class SmfAuthService
{
    /**
     * Get SMF cookie details so we can clear it on logout.
     *
     * @return array{name:?string,path:?string,domain:?string}
     */
    public function getSmfCookieDetails(): array
    {
        $settings = $this->readForumSettings();

        $name = $this->getCookieName();
        $path = isset($settings['cookiepath']) && is_string($settings['cookiepath']) && $settings['cookiepath'] !== ''
            ? $settings['cookiepath']
            : '/';
        $domain = isset($settings['cookiedomain']) && is_string($settings['cookiedomain']) && $settings['cookiedomain'] !== ''
            ? $settings['cookiedomain']
            : null;

        return [
            'name' => $name ?: null,
            'path' => $path,
            'domain' => $domain,
        ];
    }

    /**
     * Attempt to authenticate the user via SMF cookie.
     */
    public function authenticateFromSmf(): ?User
    {
        $cookieName = $this->getCookieName();
        if ($cookieName === null || $cookieName === '') {
            return null;
        }

        $cookieValue = $this->getRawCookie($cookieName);
        if ($cookieValue === null || $cookieValue === '') {
            return null;
        }

        $cookie = $this->decodeSmfCookie($cookieValue);
        if ($cookie === null) {
            return null;
        }

        $smfId = (int) ($cookie['id'] ?? 0);
        $cookieHash = (string) ($cookie['hash'] ?? '');
        $expiresAt = (int) ($cookie['expires_at'] ?? 0);

        if ($smfId <= 0 || $cookieHash === '') {
            return null;
        }

        // If SMF gave us an expiry timestamp, enforce it. (0 means "no expiry" for some setups)
        if ($expiresAt > 0 && time() > $expiresAt) {
            return null;
        }

        $authSecret = $this->getAuthSecret();
        if ($authSecret === null || $authSecret === '') {
            return null;
        }

        // Verify against SMF Database (connection uses prefix, so "members" -> "smf_members")
        try {
            $smfUser = DB::connection('smf')->table('members')
                ->select([
                    'id_member',
                    'member_name',
                    'real_name',
                    'email_address',
                    'passwd',
                    'password_salt',
                    'id_group',
                    'additional_groups',
                    'is_activated',
                ])
                ->where('id_member', $smfId)
                ->first();
        } catch (\Throwable) {
            return null;
        }

        if (!$smfUser) {
            return null;
        }

        // Block not-activated accounts (SMF uses several activation states; 0 = not activated)
        if (isset($smfUser->is_activated) && (int) $smfUser->is_activated === 0) {
            return null;
        }

        // SMF 2.1 cookie hash = hash_hmac('sha512', members.passwd, auth_secret . members.password_salt)
        $expected = hash_hmac('sha512', (string) $smfUser->passwd, $authSecret . (string) $smfUser->password_salt);

        if (!hash_equals($expected, $cookieHash)) {
            return null;
        }

        $role = $this->mapRoleFromGroups((int) $smfUser->id_group, (string) ($smfUser->additional_groups ?? ''));

        $name = Str::limit((string) ($smfUser->real_name ?: $smfUser->member_name), 191, '');
        $email = Str::limit((string) $smfUser->email_address, 191, '');

        // Sync with Local User (directory app tables).
        // - Primary key is SMF ID (smf_id)
        // - Fallback to email match to avoid duplicates on first login
        $localUser = User::where('smf_id', $smfId)->first();

        if (!$localUser && $email !== '') {
            $localUser = User::where('email', $email)->first();
        }

        if (!$localUser) {
            $localUser = new User();
            $localUser->password = bcrypt('smf_synced_' . Str::random(32));
        }

        $localUser->fill([
            'name' => $name,
            'email' => $email,
            'smf_id' => $smfId,
            'role' => $role,
        ]);

        $localUser->save();

        return $localUser;
    }

    private function getRawCookie(string $cookieName): ?string
    {
        // Prefer raw PHP cookies since SMF cookies are not Laravel-encrypted.
        if (isset($_COOKIE[$cookieName]) && is_string($_COOKIE[$cookieName])) {
            return $_COOKIE[$cookieName];
        }

        $value = Cookie::get($cookieName);

        return is_string($value) ? $value : null;
    }

    /**
     * Decode SMF auth cookie (2.1 JSON_FORCE_OBJECT OR legacy serialized array).
     *
     * Expected output keys: id, hash, expires_at
     */
    private function decodeSmfCookie(string $cookieValue): ?array
    {
        $cookieValue = trim($cookieValue);

        // SMF 2.1 uses JSON_FORCE_OBJECT => keys are "0","1","2",...
        $decoded = json_decode($cookieValue, true);
        if (!is_array($decoded)) {
            // Some environments may URL-encode cookie values.
            $decoded = json_decode(urldecode($cookieValue), true);
        }

        if (is_array($decoded) && (isset($decoded['0']) || isset($decoded[0]))) {
            return [
                'id' => (int) ($decoded[0] ?? $decoded['0'] ?? 0),
                'hash' => (string) ($decoded[1] ?? $decoded['1'] ?? ''),
                'expires_at' => (int) ($decoded[2] ?? $decoded['2'] ?? 0),
            ];
        }

        // Legacy format: PHP serialized array (SMF 2.0 -> 2.1 upgrades)
        $legacy = @unserialize($cookieValue, ['allowed_classes' => false]);
        if (is_array($legacy) && (isset($legacy[0]) || isset($legacy['0']))) {
            return [
                'id' => (int) ($legacy[0] ?? $legacy['0'] ?? 0),
                'hash' => (string) ($legacy[1] ?? $legacy['1'] ?? ''),
                'expires_at' => (int) ($legacy[2] ?? $legacy['2'] ?? 0),
            ];
        }

        return null;
    }

    private function mapRoleFromGroups(int $primaryGroupId, string $additionalGroupsCsv): string
    {
        $adminGroups = config('smf.admin_group_ids', [1]);
        $modGroups = config('smf.moderator_group_ids', [2]);

        $groups = [$primaryGroupId];
        $additionalGroupsCsv = trim($additionalGroupsCsv);

        if ($additionalGroupsCsv !== '') {
            $groups = array_merge($groups, array_map('intval', array_filter(explode(',', $additionalGroupsCsv))));
        }

        if (count(array_intersect($groups, (array) $adminGroups)) > 0) {
            return 'admin';
        }

        if (count(array_intersect($groups, (array) $modGroups)) > 0) {
            return 'moderator';
        }

        return 'user';
    }

    private function getCookieName(): ?string
    {
        $cookieName = config('smf.cookie_name') ?: env('SMF_COOKIE_NAME');

        if ($cookieName) {
            return (string) $cookieName;
        }

        $settings = $this->readForumSettings();

        return $settings['cookiename'] ?? null;
    }

    private function getAuthSecret(): ?string
    {
        $authSecret = config('smf.auth_secret') ?: env('SMF_AUTH_SECRET');

        if ($authSecret) {
            return (string) $authSecret;
        }

        $settings = $this->readForumSettings();

        return $settings['auth_secret'] ?? null;
    }

    private function readForumSettings(): array
    {
        static $cache = null;

        if (is_array($cache)) {
            return $cache;
        }

        $settingsPath = (string) (config('smf.settings_path') ?: env('SMF_SETTINGS_PATH') ?: '');
        if ($settingsPath === '') {
            return $cache = [];
        }

        $realPath = realpath($settingsPath) ?: $settingsPath;
        if (!is_file($realPath)) {
            return $cache = [];
        }

        /** @var array{cookiename?:string,auth_secret?:string,boardurl?:string,db_prefix?:string,cookiepath?:string,cookiedomain?:string} $vars */
        $vars = (static function (string $settingsPath): array {
            $cookiename = null;
            $auth_secret = null;
            $boardurl = null;
            $db_prefix = null;
            $cookiepath = null;
            $cookiedomain = null;

            require $settingsPath;

            return [
                'cookiename' => is_string($cookiename) ? $cookiename : null,
                'auth_secret' => is_string($auth_secret) ? $auth_secret : null,
                'boardurl' => is_string($boardurl) ? $boardurl : null,
                'db_prefix' => is_string($db_prefix) ? $db_prefix : null,
                'cookiepath' => is_string($cookiepath) ? $cookiepath : null,
                'cookiedomain' => is_string($cookiedomain) ? $cookiedomain : null,
            ];
        })($realPath);

        return $cache = $vars;
    }
}
