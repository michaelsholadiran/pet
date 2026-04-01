<?php

namespace App\Filament\Resources\DeliveryZones\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShippingRatesRelationManager extends RelationManager
{
    protected static string $relationship = 'shippingRates';

    protected static ?string $title = 'Shipping Rates';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('min_order_amount')
                    ->numeric()
                    ->default(0)
                    ->suffix('¢'),
                TextInput::make('rate')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->suffix('¢'),
                TextInput::make('estimated_days_min')
                    ->numeric()
                    ->minValue(0),
                TextInput::make('estimated_days_max')
                    ->numeric()
                    ->minValue(0),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('min_order_amount')->suffix('¢'),
                TextColumn::make('rate')
                    ->formatStateUsing(fn ($s) => '₦'.number_format($s / 100, 2)),
                TextColumn::make('estimated_days_min')
                    ->formatStateUsing(fn ($s, $r) => $r->estimated_days_min && $r->estimated_days_max
                        ? "{$r->estimated_days_min}-{$r->estimated_days_max} days"
                        : '-'),
                IconColumn::make('is_active')->boolean(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
