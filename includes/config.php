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
        : 'US';
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

// Currency by country: Nigeria = NGN, all others = USD
define('CURRENCY_IS_NGN', (isset($_COOKIE[COUNTRY_COOKIE_NAME]) && $_COOKIE[COUNTRY_COOKIE_NAME] === 'NG'));
define('DELIVERY_FEE_NGN', 4800);
define('DELIVERY_FEE_USD', 15);

// Site name for titles
define('SITE_NAME', 'Puppiary');

// Google Tag Manager ID
define('GTM_ID', 'GTM-TD5BTHNQ');

// Year for copyright
define('COPYRIGHT_YEAR', date('Y'));

// PayPal: switch PAYPAL_MODE to 'sandbox' or 'live' to use the corresponding keys.
$paypalMode = getenv('PAYPAL_MODE');
$paypalMode = ($paypalMode !== false && $paypalMode !== '') ? strtolower(trim($paypalMode)) : 'live';
define('PAYPAL_MODE', $paypalMode);

// Sandbox keys (for testing)
$paypalSandboxClientId    = getenv('PAYPAL_SANDBOX_CLIENT_ID')    ?: 'AcNlmvj40t_sA-iOtMX5Db_HJinHUwRt8mkjCG7UMNHhJVI_Bn7_7fxzA7Blx3K9MCPhWo6XyRIe5vWO';
$paypalSandboxClientSecret = getenv('PAYPAL_SANDBOX_CLIENT_SECRET') ?: 'ELHClcs18bauyOBznWYIA3UghfYdA8bZwF5pr4a16MCGw_sTv_L_D7caGYsdrK5QUana_ngGCulC68Ll';

// Live (production) keys
$paypalLiveClientId       = getenv('PAYPAL_LIVE_CLIENT_ID')       ?: 'AYxM7dd1qRXXL84SsvOnuZcnn2_VbOsymZdYWGu4tQSKIbFMlBYoh-EWayFd-mrN0fHoMRrLkLUQQdw5';
$paypalLiveClientSecret   = getenv('PAYPAL_LIVE_CLIENT_SECRET')   ?: 'EN_Jwy4eh00LWktuNiI6HbBGnhrrp0HNf1GLIgmjIBg4hsaUhIccecTXDGUzAH3yc8e-_9TtCzLxAq6z';

if (PAYPAL_MODE === 'sandbox') {
    define('PAYPAL_CLIENT_ID', $paypalSandboxClientId);
    define('PAYPAL_CLIENT_SECRET', $paypalSandboxClientSecret);
    define('PAYPAL_API_BASE_URL', 'https://api-m.sandbox.paypal.com');
    define('PAYPAL_SDK_URL', 'https://www.sandbox.paypal.com/web-sdk/v6/core');
} else {
    define('PAYPAL_CLIENT_ID', $paypalLiveClientId);
    define('PAYPAL_CLIENT_SECRET', $paypalLiveClientSecret);
    define('PAYPAL_API_BASE_URL', 'https://api-m.paypal.com');
    define('PAYPAL_SDK_URL', 'https://www.paypal.com/web-sdk/v6/core');
}
