<?php

namespace App\Filament\Resources\ShippingRates\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ShippingRateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('delivery_zone_id')
                    ->relationship('deliveryZone', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('min_order_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('rate')
                    ->required()
                    ->numeric(),
                TextInput::make('estimated_days_min')
                    ->numeric(),
                TextInput::make('estimated_days_max')
                    ->numeric(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
