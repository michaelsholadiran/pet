<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Image')
                    ->disk('public')
                    ->circular(false)
                    ->defaultImageUrl(fn ($record) => 'https://placehold.co/80x80/e2e8f0/64748b?text=No+img'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sku')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price')
                    ->money('NGN')
                    ->sortable(),
                TextColumn::make('sale_price')
                    ->money('NGN')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('stock_quantity')
                    ->sortable(),
                TextColumn::make('catalog_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'bundle' => 'Bundle',
                        default => 'Simple',
                    }),
                TextColumn::make('product_type')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => $state ? ucfirst($state) : '—'),
                TextColumn::make('breed_size')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => $state ? ucfirst($state) : '—')
                    ->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                SelectFilter::make('catalog_type')
                    ->options(\App\Models\Product::catalogTypes()),
                SelectFilter::make('product_type')
                    ->options(\App\Models\Product::productTypes()),
                SelectFilter::make('breed_size')
                    ->options(\App\Models\Product::breedSizes()),
                SelectFilter::make('is_active')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
