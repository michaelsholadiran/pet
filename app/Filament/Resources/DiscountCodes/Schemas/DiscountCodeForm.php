<?php

namespace App\Filament\Resources\DiscountCodes\Schemas;

use App\Models\DiscountCode;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DiscountCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Code')
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->uppercase(),
                        Select::make('type')
                            ->options(DiscountCode::types())
                            ->required()
                            ->live(),
                        TextInput::make('value')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->suffix(fn ($get) => $get('type') === 'percentage' ? '%' : '¢'),
                        TextInput::make('min_order_amount')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('¢'),
                        Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
                Section::make('Limits & Dates')
                    ->schema([
                        TextInput::make('max_uses')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('Unlimited'),
                        TextInput::make('used_count')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(),
                        Toggle::make('first_purchase_only'),
                        DateTimePicker::make('starts_at'),
                        DateTimePicker::make('expires_at'),
                    ])->columns(2),
            ]);
    }
}
