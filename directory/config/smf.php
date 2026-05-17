<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SMF Settings.php Path
    |--------------------------------------------------------------------------
    |
    | We read SMF's Settings.php for cookiename/auth_secret/boardurl when env
    | vars are not provided. This avoids duplicating sensitive secrets.
    |
    */
    'settings_path' => env('SMF_SETTINGS_PATH', base_path('../forum/Settings.php')),

    /*
    |--------------------------------------------------------------------------
    | SMF Cookie + Auth Secret (optional overrides)
    |--------------------------------------------------------------------------
    |
    | If not set, the app will attempt to read these from Settings.php.
    |
    */
    'cookie_name' => env('SMF_COOKIE_NAME'),
    'auth_secret' => env('SMF_AUTH_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | SMF URL (used for redirects)
    |--------------------------------------------------------------------------
    */
    'url' => env('SMF_URL', 'http://localhost/wlc/forum'),

    /*
    |--------------------------------------------------------------------------
    | Role Mapping (Group IDs)
    |--------------------------------------------------------------------------
    |
    | Defaults match SMF defaults:
    | - 1 = Administrator
    | - 2 = Global Moderator
    |
    */
    'admin_group_ids' => array_values(array_filter(array_map('intval', explode(',', (string) env('SMF_ADMIN_GROUP_IDS', '1'))))),
    'moderator_group_ids' => array_values(array_filter(array_map('intval', explode(',', (string) env('SMF_MOD_GROUP_IDS', '2'))))),
];

