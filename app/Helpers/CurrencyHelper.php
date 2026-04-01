<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cookie;

class CurrencyHelper
{
    public static function isNgn(): bool
    {
        $country = Cookie::get(config('puppiary.country_cookie'), 'US');

        return $country === 'NG';
    }

    public static function currency(): string
    {
        return self::isNgn() ? 'NGN' : 'USD';
    }

    public static function symbol(): string
    {
        return self::isNgn() ? '₦' : '$';
    }

    public static function deliveryFee(): float
    {
        return self::isNgn()
            ? config('puppiary.delivery_fee_ngn', 4800)
            : config('puppiary.delivery_fee_usd', 15);
    }

    public static function formatProductPrice(array $product): array
    {
        if (self::isNgn()) {
            $val = (float) ($product['price'] ?? 0);

            return ['symbol' => '₦', 'value' => $val, 'formatted' => number_format($val, 2, '.', ',')];
        }
        $usd = $product['price_usd'] ?? round(($product['price'] ?? 0) / 1500, 2);

        return ['symbol' => '$', 'value' => $usd, 'formatted' => number_format((float) $usd, 2, '.', ',')];
    }
}
