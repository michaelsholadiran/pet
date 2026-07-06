<?php
/**
 * Shared product price formatting for server-rendered pages.
 */
if (!function_exists('format_price_php')) {
    function format_price_php($price) {
        return number_format((float) $price, 2, '.', ',');
    }
}

if (!function_exists('product_display_price')) {
    function product_display_price($p) {
        if (defined('CURRENCY_IS_NGN') && CURRENCY_IS_NGN) {
            return ['symbol' => '₦', 'value' => $p['price'], 'formatted' => format_price_php($p['price'])];
        }
        $usd = isset($p['price_usd']) ? $p['price_usd'] : round($p['price'] / 1500, 2);
        return ['symbol' => '$', 'value' => $usd, 'formatted' => number_format((float) $usd, 2, '.', ',')];
    }
}
