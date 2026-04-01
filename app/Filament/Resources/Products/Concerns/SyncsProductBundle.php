<?php

namespace App\Filament\Resources\Products\Concerns;

use App\Models\BundleItem;
use App\Models\Product;

trait SyncsProductBundle
{
    protected function syncBundleComposition(): void
    {
        $product = $this->record;
        $data = $this->form->getState();

        if (($data['catalog_type'] ?? Product::CATALOG_SIMPLE) !== Product::CATALOG_BUNDLE) {
            BundleItem::query()->where('bundle_product_id', $product->id)->forceDelete();
            $product->update([
                'original_price' => null,
                'allow_partial_stock' => false,
            ]);

            return;
        }

        BundleItem::query()->where('bundle_product_id', $product->id)->forceDelete();

        $sort = 0;
        foreach ($data['bundle_items'] ?? [] as $row) {
            $cid = $row['component_product_id'] ?? null;
            if (! $cid || (int) $cid === (int) $product->id) {
                continue;
            }

            BundleItem::query()->create([
                'bundle_product_id' => $product->id,
                'component_product_id' => (int) $cid,
                'quantity' => max(1, (int) ($row['quantity'] ?? 1)),
                'sort_order' => $sort++,
            ]);
        }

        $product->refresh();
        $product->update([
            'original_price' => $product->computeOriginalPriceMinor(),
        ]);
    }
}
