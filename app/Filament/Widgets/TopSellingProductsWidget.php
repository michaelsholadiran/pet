<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopSellingProductsWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $subQuery = OrderItem::query()
            ->selectRaw('COALESCE(product_id, 0) as id, product_id, product_name, SUM(quantity) as total_sold, SUM(quantity * price) as revenue')
            ->whereHas('order', fn (Builder $q) => $q->where('status', '!=', 'cancelled'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->orderBy('product_id')
            ->limit(10);

        $outerQuery = OrderItem::query()
            ->fromSub($subQuery, 'top_products')
            ->select('top_products.*')
            ->orderByDesc('top_products.total_sold');

        return $table
            ->query($outerQuery)
            ->defaultKeySort(false)
            ->columns([
                TextColumn::make('product_name')
                    ->label('Product'),
                TextColumn::make('total_sold')
                    ->label('Units Sold'),
                TextColumn::make('revenue')
                    ->label('Revenue')
                    ->formatStateUsing(fn ($s) => '₦'.number_format($s / 100, 2)),
            ])
            ->paginated(false)
            ->heading('Top Selling Products')
            ->recordUrl(fn ($record) => ! empty($record->product_id)
                ? route('filament.admin.resources.products.view', ['record' => $record->product_id])
                : null);
    }
}
