<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('rating')
                    ->options([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5])
                    ->required(),
                TextInput::make('title')->maxLength(255),
                Textarea::make('comment')->columnSpanFull(),
                Toggle::make('is_approved')->default(false),
                Toggle::make('is_featured')->default(false),
                TextInput::make('puppy_age_at_review')
                    ->label('Puppy age at review (weeks)')
                    ->numeric()
                    ->minValue(0),
                TextInput::make('breed')->maxLength(255),
                TextInput::make('author_name')->label('Author (guest)'),
                TextInput::make('author_email')->email()->label('Author email (guest)'),
            ]);
    }
}
