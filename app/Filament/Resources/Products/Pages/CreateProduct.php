<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\Concerns\SyncsProductBundle;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateProduct extends CreateRecord
{
    use SyncsProductBundle;

    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (($data['catalog_type'] ?? Product::CATALOG_SIMPLE) === Product::CATALOG_BUNDLE) {
            $rows = array_filter($data['bundle_items'] ?? [], fn ($r) => ! empty($r['component_product_id'] ?? null));
            if ($rows === []) {
                throw ValidationException::withMessages([
                    'bundle_items' => 'Add at least one product to the bundle.',
                ]);
            }
        }

        unset($data['bundle_items']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->syncBundleComposition();
    }
}
