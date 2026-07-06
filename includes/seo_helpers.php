<?php
/**
 * Structured data and URL helpers for SEO.
 */
require_once __DIR__ . '/config.php';

if (!function_exists('puppiary_abs_url')) {
    function puppiary_abs_url($path) {
        if ($path === '' || $path === null) {
            return SITE_URL;
        }
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }
        return rtrim(SITE_URL, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('puppiary_organization_ld')) {
    function puppiary_organization_ld() {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => SITE_NAME,
            'url' => SITE_URL,
            'logo' => puppiary_abs_url('/logo.webp'),
            'sameAs' => [
                'https://www.instagram.com/puppiaryhq',
                'https://www.tiktok.com/@puppiaryhq',
                'https://twitter.com/puppiaryhq',
            ],
        ];
    }
}

if (!function_exists('puppiary_product_image_urls')) {
    function puppiary_product_image_urls(array $product) {
        $images = [];
        foreach ($product['images'] ?? [] as $img) {
            $images[] = puppiary_abs_url($img);
        }
        return $images;
    }
}

if (!function_exists('puppiary_product_ld')) {
    function puppiary_product_ld(array $product) {
        $price = isset($product['price']) ? (float) $product['price'] : 0;
        $price_usd = isset($product['price_usd']) ? (float) $product['price_usd'] : round($price / 1500, 2);
        $currency = (defined('CURRENCY_IS_NGN') && CURRENCY_IS_NGN) ? 'NGN' : 'USD';
        $price_value = $currency === 'NGN' ? $price : $price_usd;
        $product_url = puppiary_abs_url('/product/' . $product['slug']);
        $availability = (isset($product['stock']) && $product['stock'] > 0)
            ? 'https://schema.org/InStock'
            : 'https://schema.org/OutOfStock';

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product['name'],
            'description' => $product['description'] ?? ($product['shortDescription'] ?? ''),
            'image' => puppiary_product_image_urls($product),
            'url' => $product_url,
            'sku' => (string) $product['id'],
            'category' => $product['category'] ?? '',
            'brand' => [
                '@type' => 'Brand',
                'name' => SITE_NAME,
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => $product_url,
                'priceCurrency' => $currency,
                'price' => $price_value,
                'availability' => $availability,
                'priceValidUntil' => date('Y-m-d', strtotime('+1 year')),
                'shippingDetails' => [
                    '@type' => 'OfferShippingDetails',
                    'deliveryTime' => [
                        '@type' => 'ShippingDeliveryTime',
                        'handlingTime' => [
                            '@type' => 'QuantitativeValue',
                            'minValue' => 1,
                            'maxValue' => 2,
                            'unitCode' => 'DAY',
                        ],
                        'transitTime' => [
                            '@type' => 'QuantitativeValue',
                            'minValue' => 2,
                            'maxValue' => 5,
                            'unitCode' => 'DAY',
                        ],
                    ],
                ],
                'hasMerchantReturnPolicy' => [
                    '@type' => 'MerchantReturnPolicy',
                    'applicableCountry' => 'NG',
                    'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
                    'merchantReturnDays' => 30,
                    'returnMethod' => 'https://schema.org/ReturnByMail',
                    'returnFees' => 'https://schema.org/ReturnShippingFees',
                ],
            ],
        ];
    }
}

if (!function_exists('puppiary_breadcrumb_ld')) {
    function puppiary_breadcrumb_ld(array $crumbs) {
        $items = [];
        foreach ($crumbs as $i => $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $crumb['name'],
                'item' => puppiary_abs_url($crumb['url']),
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
}

if (!function_exists('puppiary_item_list_ld')) {
    function puppiary_item_list_ld(array $products) {
        $elements = [];
        foreach ($products as $i => $product) {
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'url' => puppiary_abs_url('/product/' . ($product['slug'] ?? $product['id'])),
                'name' => $product['name'] ?? '',
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'itemListElement' => $elements,
        ];
    }
}

if (!function_exists('puppiary_faq_ld')) {
    function puppiary_faq_ld(array $faqs) {
        $mainEntity = [];
        foreach ($faqs as $faq) {
            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity,
        ];
    }
}
