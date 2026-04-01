<?php

namespace Database\Seeders;

use App\Models\BundleItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * Optional extra bundle products (catalog_type = bundle).
 * Main starter kit is seeded in {@see ProductSeeder::seedStarterBundle()}.
 */
class ProductBundleSeeder extends Seeder
{
    public function run(): void
    {
        $simple = Product::query()
            ->where(function ($q) {
                $q->where('catalog_type', Product::CATALOG_SIMPLE)
                    ->orWhereNull('catalog_type');
            })
            ->orderBy('id')
            ->get();

        if ($simple->count() < 2) {
            $this->command?->warn('Need at least 2 simple products. Run ProductSeeder first.');

            return;
        }

        $bundle = Product::firstOrCreate(
            ['slug' => 'teething-play-combo'],
            [
                'name' => 'Teething & Play Combo',
                'description' => '<p>Chew toys and play picks bundled for teething pups.</p>',
                'short_description' => 'Play-focused mini kit.',
                'category' => 'Starter Kit',
                'price' => 18000,
                'sale_price' => null,
                'sku' => 'SKU-BUNDLE-TEETH',
                'stock_quantity' => 0,
                'allow_partial_stock' => false,
                'product_type' => 'kit',
                'catalog_type' => Product::CATALOG_BUNDLE,
                'breed_size' => 'medium',
                'is_active' => true,
            ]
        );

        if ($bundle->bundleItems()->exists()) {
            $bundle->update(['original_price' => $bundle->computeOriginalPriceMinor()]);

            return;
        }

        $pick = $simple->take(2);
        $sort = 0;
        foreach ($pick as $p) {
            BundleItem::query()->create([
                'bundle_product_id' => $bundle->id,
                'component_product_id' => $p->id,
                'quantity' => 1,
                'sort_order' => $sort++,
            ]);
        }

        $bundle->refresh();
        $bundle->update(['original_price' => $bundle->computeOriginalPriceMinor()]);
    }
}
