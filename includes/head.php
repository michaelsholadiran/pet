<?php
/**
 * Reusable <head> for all pages. Set before including:
 *   $page_title       - e.g. "Shop - Puppiary"
 *   $page_description - meta description
 *   $page_canonical   - path only, e.g. "/products" (will be prefixed with SITE_URL)
 *   $page_keywords    - optional meta keywords
 *   $robots_noindex   - optional true for checkout/success/404
 *   $extra_head       - optional HTML (e.g. extra preload, meta)
 */
require_once __DIR__ . '/config.php';
$canonical_url = SITE_URL . (isset($page_canonical) ? $page_canonical : '/');
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
    <?php if (!empty($robots_noindex)): ?>
    <meta name="robots" content="noindex,nofollow">
    <?php endif; ?>
    <meta name="description" content="<?php echo htmlspecialchars($page_description ?? 'Puppiary - Puppy toys, teething & starter kits.'); ?>">
    <?php if (!empty($page_keywords)): ?>
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    <?php endif; ?>
    <title><?php echo htmlspecialchars($page_title ?? SITE_NAME); ?></title>
    <!-- Preconnect to critical origins -->
    <link rel="preconnect" href="https://fonts.cdnfonts.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.cdnfonts.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
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
    <script src="/js/seo.js" defer></script>
    <?php if (!empty($extra_head)) echo $extra_head . "\n"; ?>
</head>
<body<?php echo !empty($body_class) ? ' class="' . htmlspecialchars($body_class) . '"' : ''; ?>>
