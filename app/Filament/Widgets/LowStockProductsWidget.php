<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockProductsWidget extends TableWidget
{
    protected static ?string $heading = 'Low Stock Products';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where('is_active', true)
                    ->where(function (Builder $q) {
                        $q->whereNull('stock_quantity')
                            ->orWhere('stock_quantity', '<=', 5);
                    })
                    ->orderBy('stock_quantity')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('sku')->placeholder('-'),
                TextColumn::make('stock_quantity')
                    ->badge()
                    ->color(fn ($state) => $state <= 0 ? 'danger' : ($state <= 3 ? 'warning' : 'gray')),
            ])
            ->paginated(false)
            ->recordUrl(fn ($record) => route('filament.admin.resources.products.edit', ['record' => $record]));
    }
}
