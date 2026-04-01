<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\Concerns\SyncsProductBundle;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditProduct extends EditRecord
{
    use SyncsProductBundle;

    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->isBundle()) {
            $data['bundle_items'] = $this->record->bundleItems()
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($b) => [
                    'component_product_id' => $b->component_product_id,
                    'quantity' => $b->quantity,
                ])
                ->values()
                ->all();
        } else {
            $data['bundle_items'] = [];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function afterSave(): void
    {
        $this->syncBundleComposition();
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
