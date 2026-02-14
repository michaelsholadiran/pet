<?php
/**
 * Site configuration - single place for SEO, URLs, and shared settings
 */
if (!defined('PUPPIARY_CONFIG_LOADED')) {
    define('PUPPIARY_CONFIG_LOADED', true);
}

// Canonical base URL (no trailing slash)
define('SITE_URL', 'https://www.puppiary.com');

// Country cookie: set once from Cloudflare HTTP_CF_IPCOUNTRY, then reuse
define('COUNTRY_COOKIE_NAME', 'puppiary_country');
define('COUNTRY_COOKIE_LIFETIME_DAYS', 365);
if (!isset($_COOKIE[COUNTRY_COOKIE_NAME])) {
    $country = isset($_SERVER['HTTP_CF_IPCOUNTRY']) && preg_match('/^[A-Z]{2}$/', $_SERVER['HTTP_CF_IPCOUNTRY'])
        ? $_SERVER['HTTP_CF_IPCOUNTRY']
        : null;
    if ($country !== null) {
        $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
        setcookie(COUNTRY_COOKIE_NAME, $country, [
            'expires' => time() + (COUNTRY_COOKIE_LIFETIME_DAYS * 86400),
            'path' => '/',
            'secure' => $is_https,
            'httponly' => false,
            'samesite' => 'Lax',
        ]);
        $_COOKIE[COUNTRY_COOKIE_NAME] = $country;
    }
}

// Site name for titles
define('SITE_NAME', 'Puppiary');

// Google Tag Manager ID
define('GTM_ID', 'GTM-TD5BTHNQ');

// Year for copyright
define('COPYRIGHT_YEAR', date('Y'));
