<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                ImageEntry::make('image_url')
                    ->label('Image')
                    ->disk('public'),
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('description')
                    ->html()
                    ->columnSpanFull(),
                TextEntry::make('price')
                    ->money('NGN'),
                TextEntry::make('sale_price')
                    ->money('NGN')
                    ->placeholder('—'),
                TextEntry::make('stock_quantity'),
                TextEntry::make('sku')
                    ->placeholder('—'),
                TextEntry::make('product_type')
                    ->formatStateUsing(fn (?string $state) => $state ? ucfirst($state) : '—'),
                TextEntry::make('breed_size')
                    ->formatStateUsing(fn (?string $state) => $state ? ucfirst($state) : '—'),
                TextEntry::make('age_min_weeks')
                    ->placeholder('—'),
                TextEntry::make('age_max_weeks')
                    ->placeholder('—'),
                TextEntry::make('is_active')
                    ->badge()
                    ->formatStateUsing(fn (bool $state) => $state ? 'Active' : 'Inactive'),
            ]);
    }
}
