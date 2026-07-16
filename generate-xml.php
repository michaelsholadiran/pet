<?php
/**
 * Google Merchant Center RSS 2.0 product feed generator.
 *
 * Run from CLI:  php generate-xml.php
 * Or visit:      /generate-xml.php
 *
 * Writes: feeds/products.xml
 * Public URL: https://www.puppiary.com/feeds/products.xml
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/products_data.php';

$feedDir = __DIR__ . '/feeds';
$feedFile = $feedDir . '/products.xml';
$baseUrl = rtrim(SITE_URL, '/');

if (!is_dir($feedDir) && !mkdir($feedDir, 0755, true) && !is_dir($feedDir)) {
    fwrite(STDERR, "Failed to create feeds directory.\n");
    exit(1);
}

/**
 * Escape text for XML element content (Google Merchant / RSS).
 */
function puppiary_xml_escape(string $value): string
{
    return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}

/**
 * Build an absolute URL; encode path segments (spaces, special chars in image paths).
 */
function puppiary_feed_absolute_url(string $baseUrl, string $path): string
{
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }

    $path = '/' . ltrim($path, '/');
    $parts = explode('/', $path);
    $encoded = array_map(static function ($segment) {
        return $segment === '' ? '' : rawurlencode(rawurldecode($segment));
    }, $parts);

    return $baseUrl . implode('/', $encoded);
}

$catalog = array_values(array_filter($products, static function ($p) {
    if (empty($p['published'])) {
        return false;
    }
    if (isset($p['list_in_catalog']) && $p['list_in_catalog'] === false) {
        return false;
    }
    return true;
}));

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . "\n";
$xml .= "  <channel>\n";
$xml .= '    <title>' . puppiary_xml_escape(SITE_NAME) . "</title>\n";
$xml .= '    <link>' . puppiary_xml_escape($baseUrl) . "</link>\n";
$xml .= '    <description>' . puppiary_xml_escape('The 0-12 Month Puppy Specialist') . "</description>\n";

foreach ($catalog as $product) {
    $id = 'PUP_' . str_pad((string) (int) $product['id'], 3, '0', STR_PAD_LEFT);
    $title = (string) ($product['name'] ?? '');
    $description = (string) ($product['description'] ?? $product['shortDescription'] ?? $title);
    $slug = (string) ($product['slug'] ?? '');
    $link = $baseUrl . '/product/' . rawurlencode($slug);

    $imagePath = '';
    if (!empty($product['images'][0])) {
        $imagePath = (string) $product['images'][0];
    }
    $imageLink = $imagePath !== '' ? puppiary_feed_absolute_url($baseUrl, $imagePath) : '';

    $price = number_format((float) ($product['price'] ?? 0), 2, '.', '') . ' NGN';
    $availability = (!empty($product['stock']) && (int) $product['stock'] > 0) ? 'in_stock' : 'out_of_stock';
    $brand = !empty($product['brand']) ? (string) $product['brand'] : SITE_NAME;

    $xml .= "    <item>\n";
    $xml .= '      <g:id>' . puppiary_xml_escape($id) . "</g:id>\n";
    $xml .= '      <g:title>' . puppiary_xml_escape($title) . "</g:title>\n";
    $xml .= '      <g:description>' . puppiary_xml_escape($description) . "</g:description>\n";
    $xml .= '      <g:link>' . puppiary_xml_escape($link) . "</g:link>\n";
    if ($imageLink !== '') {
        $xml .= '      <g:image_link>' . puppiary_xml_escape($imageLink) . "</g:image_link>\n";
    }
    $xml .= '      <g:price>' . puppiary_xml_escape($price) . "</g:price>\n";
    $xml .= '      <g:brand>' . puppiary_xml_escape($brand) . "</g:brand>\n";
    $xml .= "      <g:condition>new</g:condition>\n";
    $xml .= '      <g:availability>' . puppiary_xml_escape($availability) . "</g:availability>\n";
    $xml .= "      <g:feed_label>NG</g:feed_label>\n";
    $xml .= "      <g:identifier_exists>false</g:identifier_exists>\n";
    $xml .= "    </item>\n";
}

$xml .= "  </channel>\n";
$xml .= "</rss>\n";

if (file_put_contents($feedFile, $xml) === false) {
    fwrite(STDERR, "Failed to write {$feedFile}\n");
    exit(1);
}

$count = count($catalog);
$message = "Generated feeds/products.xml with {$count} product(s).";

if (PHP_SAPI === 'cli') {
    echo $message . "\n";
    echo "URL: {$baseUrl}/feeds/products.xml\n";
    exit(0);
}

header('Content-Type: text/plain; charset=UTF-8');
echo $message . "\n";
echo "URL: {$baseUrl}/feeds/products.xml\n";
