<?php
/**
 * Dynamic XML sitemap generated from products_data.php.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/products_data.php';

header('Content-Type: application/xml; charset=utf-8');

$static_pages = [
    ['path' => '/', 'changefreq' => 'weekly', 'priority' => '1.0'],
    ['path' => '/products', 'changefreq' => 'daily', 'priority' => '0.9'],
    ['path' => '/about', 'changefreq' => 'yearly', 'priority' => '0.5'],
    ['path' => '/contact', 'changefreq' => 'yearly', 'priority' => '0.5'],
    ['path' => '/faq', 'changefreq' => 'monthly', 'priority' => '0.5'],
    ['path' => '/privacy', 'changefreq' => 'yearly', 'priority' => '0.3'],
    ['path' => '/terms', 'changefreq' => 'yearly', 'priority' => '0.3'],
    ['path' => '/refund', 'changefreq' => 'yearly', 'priority' => '0.3'],
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($static_pages as $page) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars(rtrim(SITE_URL, '/') . $page['path'], ENT_XML1) . "</loc>\n";
    if (!empty($page['changefreq'])) {
        echo '    <changefreq>' . htmlspecialchars($page['changefreq'], ENT_XML1) . "</changefreq>\n";
    }
    if (!empty($page['priority'])) {
        echo '    <priority>' . htmlspecialchars($page['priority'], ENT_XML1) . "</priority>\n";
    }
    echo "  </url>\n";
}

foreach ($products as $product) {
    if (empty($product['published'])) {
        continue;
    }
    if (isset($product['list_in_catalog']) && $product['list_in_catalog'] === false) {
        continue;
    }
    if (empty($product['slug'])) {
        continue;
    }
    $loc = rtrim(SITE_URL, '/') . '/product/' . $product['slug'];
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo "    <priority>0.8</priority>\n";
    echo "  </url>\n";
}

echo "</urlset>\n";
