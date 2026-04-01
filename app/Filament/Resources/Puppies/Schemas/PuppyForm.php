<?php

namespace App\Filament\Resources\Puppies\Schemas;

use App\Models\Puppy;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PuppyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('breed')->maxLength(255),
                DatePicker::make('birth_date')->label('Birth date'),
                TextInput::make('weight')
                    ->numeric()
                    ->suffix('kg'),
                Select::make('size_category')
                    ->options(Puppy::sizeCategories())
                    ->placeholder('Select size'),
            ]);
    }
}
