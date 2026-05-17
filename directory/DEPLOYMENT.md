# WeLiveCrypto Directory Deployment Guide

## 1. Environment Configuration (.env)

Ensure your `.env` file is configured for production.

### Database
Both Laravel and SMF must share the same database for the authentication/session bridging to work (as implemented in `app/Services/SmfAuthService.php`).

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wlcsmf            # Must contain both Directory tables (projects, reviews, etc.) AND SMF tables (smf_members, ...)
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

> Important: **Never run `php artisan migrate:fresh`** on this shared database. It will drop tables and can destroy your SMF forum data.

### SMF Integration (Cookies)
To allow Laravel to read SMF cookies, both apps must be on the same parent domain (e.g., `welivecrypto.com`).

**In `directory/.env`:**
```env
# Set this to your top-level domain with a leading dot
SESSION_DOMAIN=.welivecrypto.com

# Must match SMF's $cookiename from forum/Settings.php
SMF_COOKIE_NAME=SMFCookie477

# Optional: let Laravel read SMF settings directly from disk (recommended on same server)
SMF_SETTINGS_PATH=C:/wamp64/www/wlc/forum/Settings.php

# Forum base URL (used for login/register redirects)
SMF_URL=http://localhost/wlc/forum
```

**In SMF (Admin Panel):**
- Go to **Server Settings > Cookies and Sessions**
- Set **Enable local storage of cookies**: unchecked (use global/subdomain cookies)
- Ensure the cookie name matches what Laravel looks for (default: `SMFCookie11`).
  - If different, set `SMF_COOKIE_NAME` (and optionally `SMF_SETTINGS_PATH`) in the Directory `.env`.

## 2. Server Setup (Apache/Nginx)

### Apache (Directory inside Subfolder)
If you host the directory at `welivecrypto.com/directory`:

1.  Point the `directory` alias to `.../directory-app/public`.
2.  Ensure `mod_rewrite` is enabled.

### Nginx (Subdomain)
If you host at `directory.welivecrypto.com`:

```nginx
server {
    listen 80;
    server_name directory.welivecrypto.com;
    root /var/www/welivecrypto/directory-app/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # ... PHP handling ...
}
```

## 3. Automation (Cron Job)

To enable the hourly health checks (Online/Offline status), add this single cron entry to your server:

```bash
* * * * * cd /path/to/directory-app && php artisan schedule:run >> /dev/null 2>&1
```

## 4. Final Verification Checklist

- [ ] **Database**: Run `php artisan migrate` to create Directory tables (`projects`, `reviews`, etc.) alongside existing SMF tables.
- [ ] **Symlink**: Run `php artisan storage:link` if you plan to upload images (not currently used but good practice).
- [ ] **Permissions**: Ensure `storage/` and `bootstrap/cache/` are writable (`chmod -R 775`).
- [ ] **Auth Test**: Log in to SMF, then visit the Directory. You should appear logged in.
- [ ] **Admin Test**: Log in as an SMF Administrator. Visit `/admin`. You should see the dashboard.

## 5. Directory Structure
- **Models**: `app/Models/` (Project, Review, Category)
- **Views**: `resources/views/livewire/` (Frontend components)
- **Auth Logic**: `app/Services/SmfAuthService.php`, `config/smf.php`, `app/Http/Middleware/SmfAuthMiddleware.php`
- **Commands**: `app/Console/Commands/MonitorProjectHealth.php`
