<?php
/**
 * Site configuration - single place for SEO, URLs, and shared settings
 */
if (!defined('PUPPIARY_CONFIG_LOADED')) {
    define('PUPPIARY_CONFIG_LOADED', true);
}

// Canonical base URL (no trailing slash)
define('SITE_URL', 'https://www.puppiary.com');

// Site name for titles
define('SITE_NAME', 'Puppiary');

// Google Tag Manager ID
define('GTM_ID', 'GTM-TD5BTHNQ');

// Year for copyright
define('COPYRIGHT_YEAR', date('Y'));
