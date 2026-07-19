<?php
/**
 * Site configuration - single place for SEO, URLs, and shared settings
 */
if (!defined('PUPPIARY_CONFIG_LOADED')) {
    define('PUPPIARY_CONFIG_LOADED', true);
}

// Canonical base URL (no trailing slash)
define('SITE_URL', 'https://www.puppiary.com');

// Market: Nigeria only (NGN). Re-enable geo detection below to serve US/USD again.
define('MARKET_COUNTRY', 'NG');
define('CURRENCY_IS_NGN', true);
define('DELIVERY_FEE_NGN', 4800);
define('DELIVERY_DAYS_MIN', 3);
define('DELIVERY_DAYS_MAX', 7);
// define('DELIVERY_FEE_USD', 15);

// Country cookie: set once from Cloudflare HTTP_CF_IPCOUNTRY, then reuse
// define('COUNTRY_COOKIE_NAME', 'puppiary_country');
// define('COUNTRY_COOKIE_LIFETIME_DAYS', 365);
// if (!isset($_COOKIE[COUNTRY_COOKIE_NAME])) {
//     $country = isset($_SERVER['HTTP_CF_IPCOUNTRY']) && preg_match('/^[A-Z]{2}$/', $_SERVER['HTTP_CF_IPCOUNTRY'])
//         ? $_SERVER['HTTP_CF_IPCOUNTRY']
//         : 'US';
//     if ($country !== null) {
//         $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
//         setcookie(COUNTRY_COOKIE_NAME, $country, [
//             'expires' => time() + (COUNTRY_COOKIE_LIFETIME_DAYS * 86400),
//             'path' => '/',
//             'secure' => $is_https,
//             'httponly' => false,
//             'samesite' => 'Lax',
//         ]);
//         $_COOKIE[COUNTRY_COOKIE_NAME] = $country;
//     }
// }

// Currency by country: Nigeria = NGN, all others = USD
// define('CURRENCY_IS_NGN', (isset($_COOKIE[COUNTRY_COOKIE_NAME]) && $_COOKIE[COUNTRY_COOKIE_NAME] === 'NG'));

// Site name for titles
define('SITE_NAME', 'Puppiary');

// SEO: default OG image path, theme color, Twitter handle
define('DEFAULT_OG_IMAGE', '/products/calming-dog-bed/calming-dog-bed-1.webp');
define('THEME_COLOR', '#e38106');
define('TWITTER_HANDLE', '@puppiaryhq');

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

/**
 * Last modified time of a project-root file (for cache-busting ?v= on CSS/JS URLs).
 *
 * @param string $relativePath path under project root, e.g. 'js/main.js' or 'css/style.css'
 */
if (!function_exists('puppiary_asset_mtime')) {
    function puppiary_asset_mtime(string $relativePath): int
    {
        $path = __DIR__ . '/../' . ltrim($relativePath, '/');
        return is_file($path) ? filemtime($path) : 0;
    }
}

/**
 * Move Sunday deliveries to Monday (no Sunday delivery).
 */
if (!function_exists('puppiary_adjust_delivery_date')) {
    function puppiary_adjust_delivery_date(DateTimeImmutable $date): DateTimeImmutable
    {
        if ((int) $date->format('N') === 7) {
            return $date->modify('+1 day');
        }

        return $date;
    }
}

/**
 * Human-readable delivery window, e.g. "Delivery between Wednesday, 15 Jul and Monday, 20 Jul".
 * Sundays are excluded — they roll forward to Monday.
 */
if (!function_exists('puppiary_delivery_window_text')) {
    function puppiary_delivery_window_text(?int $minDays = null, ?int $maxDays = null): string
    {
        $minDays = $minDays ?? (defined('DELIVERY_DAYS_MIN') ? DELIVERY_DAYS_MIN : 3);
        $maxDays = $maxDays ?? (defined('DELIVERY_DAYS_MAX') ? DELIVERY_DAYS_MAX : 7);

        $today = new DateTimeImmutable('today');
        $earliest = puppiary_adjust_delivery_date($today->modify('+' . $minDays . ' days'));
        $latest = puppiary_adjust_delivery_date($today->modify('+' . $maxDays . ' days'));

        $earliestLabel = $earliest->format('l, j M');
        $latestLabel = $latest->format('l, j M');

        if ($earliest->format('Y-m-d') === $latest->format('Y-m-d')) {
            return 'Delivery on ' . $earliestLabel;
        }

        return 'Delivery between ' . $earliestLabel . ' and ' . $latestLabel;
    }
}

/**
 * Shorter delivery window for compact UI (e.g. mobile marquee).
 */
if (!function_exists('puppiary_delivery_window_short_text')) {
    function puppiary_delivery_window_short_text(?int $minDays = null, ?int $maxDays = null): string
    {
        $minDays = $minDays ?? (defined('DELIVERY_DAYS_MIN') ? DELIVERY_DAYS_MIN : 3);
        $maxDays = $maxDays ?? (defined('DELIVERY_DAYS_MAX') ? DELIVERY_DAYS_MAX : 7);

        $today = new DateTimeImmutable('today');
        $earliest = puppiary_adjust_delivery_date($today->modify('+' . $minDays . ' days'));
        $latest = puppiary_adjust_delivery_date($today->modify('+' . $maxDays . ' days'));

        $earliestLabel = $earliest->format('D, j M');
        $latestLabel = $latest->format('D, j M');

        if ($earliest->format('Y-m-d') === $latest->format('Y-m-d')) {
            return 'Delivery ' . $earliestLabel;
        }

        return 'Delivery ' . $earliestLabel . ' – ' . $latestLabel;
    }
}

/**
 * Lagos estimate for product/checkout, e.g. "Estimated delivery within Lagos: Jul 22–27".
 */
if (!function_exists('puppiary_delivery_lagos_estimate_text')) {
    function puppiary_delivery_lagos_estimate_text(?int $minDays = null, ?int $maxDays = null): string
    {
        $minDays = $minDays ?? (defined('DELIVERY_DAYS_MIN') ? DELIVERY_DAYS_MIN : 3);
        $maxDays = $maxDays ?? (defined('DELIVERY_DAYS_MAX') ? DELIVERY_DAYS_MAX : 7);

        $today = new DateTimeImmutable('today');
        $earliest = puppiary_adjust_delivery_date($today->modify('+' . $minDays . ' days'));
        $latest = puppiary_adjust_delivery_date($today->modify('+' . $maxDays . ' days'));

        if ($earliest->format('Y-m-d') === $latest->format('Y-m-d')) {
            return 'Estimated delivery within Lagos: ' . $earliest->format('M j');
        }

        if ($earliest->format('M') === $latest->format('M')) {
            $range = $earliest->format('M j') . '–' . $latest->format('j');
        } else {
            $range = $earliest->format('M j') . '–' . $latest->format('M j');
        }

        return 'Estimated delivery within Lagos: ' . $range;
    }
}
