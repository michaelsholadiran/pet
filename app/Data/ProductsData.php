<?php

namespace App\Data;

use App\Models\Product;

class ProductsData
{
    /**
     * Active products for storefront, cart, and checkout scripts.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        return Product::query()
            ->where('is_active', true)
            ->with('images')
            ->orderBy('name')
            ->get()
            ->map(fn (Product $product) => $product->toCatalogArray())
            ->all();
    }

    public static function findBySlug(string $slug): ?array
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with('images')
            ->first();

        return $product?->toCatalogArray();
    }

    public static function findById(int $id): ?array
    {
        $product = Product::query()
            ->where('is_active', true)
            ->with('images')
            ->find($id);

        return $product?->toCatalogArray();
    }

    public static function states(string $country): array
    {
        return match (strtoupper($country)) {
            'NG', 'NIGERIA' => ['Lagos'],
            default => ['California'],
        };
    }
}
