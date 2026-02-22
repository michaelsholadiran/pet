<?php
/**
 * Reusable <head> for all pages. Set before including:
 *   $page_title       - e.g. "Shop - Puppiary"
 *   $page_description - meta description
 *   $page_canonical   - path only, e.g. "/products" (will be prefixed with SITE_URL)
 *   $page_keywords    - optional meta keywords
 *   $robots_noindex   - optional true for checkout/success/404
 *   $page_og_image    - optional OG image path (default from DEFAULT_OG_IMAGE)
 *   $page_og_type     - optional og:type (default 'website')
 *   $json_ld_scripts  - optional array of JSON-LD objects to output as script tags
 *   $extra_head       - optional HTML (e.g. extra preload, meta)
 */
require_once __DIR__ . '/config.php';
$canonical_url = SITE_URL . (isset($page_canonical) ? $page_canonical : '/');
$seo_og_image = isset($page_og_image) ? $page_og_image : (defined('DEFAULT_OG_IMAGE') ? DEFAULT_OG_IMAGE : '');
$seo_og_image_url = $seo_og_image ? (preg_match('#^https?://#', $seo_og_image) ? $seo_og_image : SITE_URL . $seo_og_image) : '';
$seo_og_type = isset($page_og_type) ? $page_og_type : 'website';
$seo_noindex = !empty($robots_noindex);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo htmlspecialchars(GTM_ID); ?>');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if ($seo_noindex): ?>
    <meta name="robots" content="noindex,nofollow">
    <?php endif; ?>
    <meta name="description" content="<?php echo htmlspecialchars($page_description ?? 'Puppiary - Puppy toys, teething & starter kits.'); ?>">
    <?php if (!empty($page_keywords)): ?>
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    <?php endif; ?>
    <meta name="theme-color" content="<?php echo htmlspecialchars(defined('THEME_COLOR') ? THEME_COLOR : '#e38106'); ?>">
    <title><?php echo htmlspecialchars($page_title ?? SITE_NAME); ?></title>
    <link rel="preconnect" href="https://fonts.cdnfonts.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.cdnfonts.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <link rel="dns-prefetch" href="https://js.paystack.co">
    <link rel="preload" href="https://fonts.cdnfonts.com/css/futura-pt" as="style">
    <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/futura-pt" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.cdnfonts.com/css/futura-pt"></noscript>
    <link rel="preload" href="/css/style.css" as="style">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="<?php echo htmlspecialchars(SITE_NAME); ?>" />
    <link rel="manifest" href="/site.webmanifest" />
    <link rel="stylesheet" href="/css/style.css">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">
    <?php if (!$seo_noindex): ?>
    <link rel="alternate" hreflang="en" href="<?php echo htmlspecialchars($canonical_url); ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo htmlspecialchars($canonical_url); ?>">
    <?php endif; ?>
    <meta property="og:site_name" content="<?php echo htmlspecialchars(SITE_NAME); ?>">
    <meta property="og:locale" content="en_US">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title ?? SITE_NAME); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description ?? 'Puppiary - Puppy toys, teething & starter kits.'); ?>">
    <meta property="og:type" content="<?php echo htmlspecialchars($seo_og_type); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>">
    <?php if ($seo_og_image_url): ?>
    <meta property="og:image" content="<?php echo htmlspecialchars($seo_og_image_url); ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <?php if (defined('TWITTER_HANDLE') && TWITTER_HANDLE): ?>
    <meta name="twitter:site" content="<?php echo htmlspecialchars(TWITTER_HANDLE); ?>">
    <?php endif; ?>
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title ?? SITE_NAME); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description ?? 'Puppiary - Puppy toys, teething & starter kits.'); ?>">
    <?php if ($seo_og_image_url): ?>
    <meta name="twitter:image" content="<?php echo htmlspecialchars($seo_og_image_url); ?>">
    <?php endif; ?>
    <script>
    window.CURRENCY = <?php echo json_encode(CURRENCY_IS_NGN ? 'NGN' : 'USD'); ?>;
    window.CURRENCY_SYMBOL = <?php echo json_encode(CURRENCY_IS_NGN ? '₦' : '$'); ?>;
    window.DELIVERY_FEE = <?php echo CURRENCY_IS_NGN ? DELIVERY_FEE_NGN : DELIVERY_FEE_USD; ?>;
    </script>
    <?php
    if (!empty($json_ld_scripts) && is_array($json_ld_scripts)) {
        foreach ($json_ld_scripts as $ld) {
            echo '<script type="application/ld+json">' . "\n" . json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n" . '</script>' . "\n";
        }
    }
    ?>
    <?php if (!empty($extra_head)) echo $extra_head . "\n"; ?>
</head>
<body<?php echo !empty($body_class) ? ' class="' . htmlspecialchars($body_class) . '"' : ''; ?>>
