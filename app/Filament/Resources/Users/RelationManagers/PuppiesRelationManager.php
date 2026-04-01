<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Models\Puppy;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PuppiesRelationManager extends RelationManager
{
    protected static string $relationship = 'puppies';

    protected static ?string $title = 'Puppies';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('breed')->maxLength(255),
                DatePicker::make('birth_date')
                    ->label('Birth date'),
                TextInput::make('weight')
                    ->numeric()
                    ->suffix('kg'),
                Select::make('size_category')
                    ->options(Puppy::sizeCategories())
                    ->placeholder('Select size'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('breed'),
                TextColumn::make('birth_date')->date(),
                TextColumn::make('weight')->suffix(' kg'),
                TextColumn::make('size_category')->badge(),
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
