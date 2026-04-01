<?php

namespace App\Filament\Resources\DeliveryZones\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DeliveryZoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Zone')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TagsInput::make('countries')
                            ->placeholder('US, CA, GB...')
                            ->helperText('ISO country codes. Leave empty for all countries.'),
                        TextInput::make('regions')
                            ->placeholder('State/province codes')
                            ->helperText('e.g. CA, NY, TX'),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
