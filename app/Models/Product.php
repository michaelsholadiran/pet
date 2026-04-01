<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    public const CATALOG_SIMPLE = 'simple';

    public const CATALOG_BUNDLE = 'bundle';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'category',
        'price',
        'sale_price',
        'original_price',
        'sku',
        'stock_quantity',
        'allow_partial_stock',
        'image_url',
        'is_active',
        'age_min_weeks',
        'age_max_weeks',
        'breed_size',
        'product_type',
        'catalog_type',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'allow_partial_stock' => 'boolean',
            'price' => 'integer',
            'sale_price' => 'integer',
            'original_price' => 'integer',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function bundleItems(): HasMany
    {
        return $this->hasMany(BundleItem::class, 'bundle_product_id')->orderBy('sort_order');
    }

    public function isBundle(): bool
    {
        return ($this->catalog_type ?? self::CATALOG_SIMPLE) === self::CATALOG_BUNDLE;
    }

    public static function componentSellingPriceMinor(?Product $component): int
    {
        if (! $component) {
            return 0;
        }

        return (int) ($component->sale_price ?? $component->price);
    }

    /**
     * Sum of (component sale/regular price × qty) for all bundle lines.
     */
    public function computeOriginalPriceMinor(): ?int
    {
        if (! $this->isBundle()) {
            return null;
        }

        $this->loadMissing('bundleItems.componentProduct');

        $sum = 0;
        foreach ($this->bundleItems as $line) {
            $sum += self::componentSellingPriceMinor($line->componentProduct) * max(1, (int) $line->quantity);
        }

        return $sum > 0 ? $sum : null;
    }

    /**
     * How many full bundles can be fulfilled from component stock (strict mode).
     */
    public function bundleFulfillableQuantity(): int
    {
        if (! $this->isBundle()) {
            return max(0, (int) $this->stock_quantity);
        }

        $this->loadMissing('bundleItems.componentProduct');

        if ($this->bundleItems->isEmpty()) {
            return 0;
        }

        $min = PHP_INT_MAX;
        foreach ($this->bundleItems as $line) {
            $c = $line->componentProduct;
            if (! $c) {
                return 0;
            }
            $per = max(1, (int) $line->quantity);
            $min = min($min, intdiv(max(0, (int) $c->stock_quantity), $per));
        }

        return $min === PHP_INT_MAX ? 0 : $min;
    }

    /**
     * Stock number exposed to storefront JSON (cart / PLP).
     */
    public function catalogStockQuantity(): int
    {
        if (! $this->isBundle()) {
            return (int) $this->stock_quantity;
        }

        if ($this->allow_partial_stock) {
            return max((int) $this->stock_quantity, 999);
        }

        return $this->bundleFulfillableQuantity();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function bundleLinesForCatalog(): array
    {
        if (! $this->isBundle()) {
            return [];
        }

        $this->loadMissing('bundleItems.componentProduct');

        $lines = [];
        foreach ($this->bundleItems as $line) {
            $c = $line->componentProduct;
            if (! $c) {
                continue;
            }
            $thumb = $c->shopImageUrls()[0] ?? null;
            $lines[] = [
                'productId' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'quantity' => max(1, (int) $line->quantity),
                'image' => $thumb,
            ];
        }

        return $lines;
    }

    /**
     * Public URLs for gallery (public disk). Used by shop views and cart JSON.
     *
     * @return array<int, string>
     */
    public function shopImageUrls(): array
    {
        $this->loadMissing('images');

        $urls = [];

        foreach ($this->images->sortBy('sort_order') as $image) {
            if (filled($image->image_url)) {
                $urls[] = asset('storage/'.$image->image_url);
            }
        }

        if ($urls === [] && filled($this->image_url)) {
            $urls[] = asset('storage/'.$this->image_url);
        }

        return $urls;
    }

    /**
     * Shape expected by Livewire shop views, cart scripts, and {@see \App\Helpers\CurrencyHelper::formatProductPrice()}.
     *
     * @return array<string, mixed>
     */
    public function toCatalogArray(): array
    {
        $images = $this->shopImageUrls();
        if ($images === []) {
            $images = ['https://placehold.co/640x480/e2e8f0/64748b?text=Product'];
        }

        $price = (int) $this->price;
        $sellMinor = (int) ($this->sale_price ?? $this->price);
        $original = $this->original_price !== null ? (int) $this->original_price : null;

        $row = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category ?? 'Shop',
            'price' => $price,
            'price_usd' => round($price / 1500, 2),
            'shortDescription' => $this->short_description ?? '',
            'description' => $this->description ?? '',
            'images' => $images,
            'stock' => $this->catalogStockQuantity(),
            'published' => (bool) $this->is_active,
            'catalogType' => $this->catalog_type ?? self::CATALOG_SIMPLE,
            'isBundle' => $this->isBundle(),
            'bundleLines' => $this->bundleLinesForCatalog(),
            'originalPrice' => $original,
            'allowPartialStock' => (bool) $this->allow_partial_stock,
        ];

        if ($this->isBundle() && $original !== null && $original > $sellMinor) {
            $row['bundleSavingsMinor'] = $original - $sellMinor;
            $row['bundleSavingsPercent'] = $original > 0
                ? round(100 * ($original - $sellMinor) / $original, 1)
                : 0;
        }

        return $row;
    }

    public static function breedSizes(): array
    {
        return [
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large',
        ];
    }

    public static function productTypes(): array
    {
        return [
            'food' => 'Food',
            'treat' => 'Treat',
            'kit' => 'Kit',
            'toy' => 'Toy',
        ];
    }

    public static function catalogTypes(): array
    {
        return [
            self::CATALOG_SIMPLE => 'Simple product',
            self::CATALOG_BUNDLE => 'Bundle (fixed kit)',
        ];
    }
}
