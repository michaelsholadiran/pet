<?php

namespace Database\Seeders;

use App\Models\BundleItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\RandomUserService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Calming Dog Bed', 'category' => 'Grooming & Comfort', 'price' => 43999, 'sale_price' => 39999, 'type' => 'toy', 'breed' => 'medium', 'stock' => 12],
            ['name' => 'No-Pull Harness', 'category' => 'Training & Safety', 'price' => 9999, 'sale_price' => null, 'type' => 'kit', 'breed' => 'small', 'stock' => 25],
            ['name' => 'Indestructible Chew Toy', 'category' => 'Play & Teething', 'price' => 8200, 'sale_price' => 6999, 'type' => 'toy', 'breed' => 'large', 'stock' => 15],
            ['name' => 'Dog Paw Washer Cup', 'category' => 'Grooming & Comfort', 'price' => 10200, 'sale_price' => null, 'type' => 'kit', 'breed' => 'small', 'stock' => 16],
            ['name' => 'Grooming Glove', 'category' => 'Grooming & Comfort', 'price' => 1500, 'sale_price' => null, 'type' => 'kit', 'breed' => 'medium', 'stock' => 14],
            ['name' => 'Feeding Bowl', 'category' => 'Feeding', 'price' => 8000, 'sale_price' => 6500, 'type' => 'food', 'breed' => 'large', 'stock' => 20],
            ['name' => 'Roll N Lick Ball', 'category' => 'Play & Teething', 'price' => 8500, 'sale_price' => null, 'type' => 'toy', 'breed' => 'medium', 'stock' => 18],
        ];

        foreach ($products as $i => $data) {
            $slug = Str::slug($data['name']);
            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'description' => '<p>'.fake()->paragraphs(3, true).'</p>',
                    'short_description' => fake()->sentence(),
                    'category' => $data['category'],
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'],
                    'sku' => 'SKU-'.strtoupper(Str::random(8)),
                    'stock_quantity' => $data['stock'],
                    'product_type' => $data['type'],
                    'breed_size' => $data['breed'],
                    'age_min_weeks' => rand(8, 12),
                    'age_max_weeks' => rand(52, 104),
                    'is_active' => true,
                ]
            );

            if ($product->wasRecentlyCreated) {
                $imgUrl = RandomUserService::imageUrl();
                $path = RandomUserService::downloadAndStore($imgUrl, 'product-images', 'product-'.$product->id);
                if ($path) {
                    $product->update(['image_url' => $path]);
                }

                for ($j = 0; $j < rand(2, 4); $j++) {
                    $imgUrl = RandomUserService::imageUrl();
                    $path = RandomUserService::downloadAndStore($imgUrl, 'product-images', 'product-'.$product->id.'-img');
                    if ($path) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_url' => $path,
                            'is_primary' => $j === 0,
                            'sort_order' => $j,
                        ]);
                    }
                }
            }
        }

        $this->seedStarterBundle();
    }

    protected function seedStarterBundle(): void
    {
        $bundle = Product::firstOrCreate(
            ['slug' => 'complete-starter-bundle'],
            [
                'name' => 'Complete Starter Bundle',
                'description' => '<p>Everything your puppy needs in the first weeks — curated in one kit.</p>',
                'short_description' => 'Curated essentials for new puppy parents.',
                'category' => 'Starter Kit',
                'price' => 45000,
                'sale_price' => null,
                'sku' => 'SKU-BUNDLE-STARTER',
                'stock_quantity' => 0,
                'allow_partial_stock' => false,
                'product_type' => 'kit',
                'catalog_type' => Product::CATALOG_BUNDLE,
                'breed_size' => 'medium',
                'age_min_weeks' => 8,
                'age_max_weeks' => 52,
                'is_active' => true,
            ]
        );

        if ($bundle->bundleItems()->exists()) {
            $bundle->update(['original_price' => $bundle->computeOriginalPriceMinor()]);

            return;
        }

        $simpleIds = Product::query()
            ->where(function ($q) {
                $q->where('catalog_type', Product::CATALOG_SIMPLE)
                    ->orWhereNull('catalog_type');
            })
            ->where('id', '!=', $bundle->id)
            ->orderBy('id')
            ->limit(4)
            ->pluck('id');

        $sort = 0;
        foreach ($simpleIds as $pid) {
            BundleItem::query()->create([
                'bundle_product_id' => $bundle->id,
                'component_product_id' => $pid,
                'quantity' => 1,
                'sort_order' => $sort++,
            ]);
        }

        $bundle->refresh();
        $bundle->update(['original_price' => $bundle->computeOriginalPriceMinor()]);
    }
}
