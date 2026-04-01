<?php

namespace App\Filament\Resources\Puppies;

use App\Filament\Resources\Puppies\Pages\CreatePuppy;
use App\Filament\Resources\Puppies\Pages\EditPuppy;
use App\Filament\Resources\Puppies\Pages\ListPuppies;
use App\Filament\Resources\Puppies\Pages\ViewPuppy;
use App\Filament\Resources\Puppies\Schemas\PuppyForm;
use App\Filament\Resources\Puppies\Schemas\PuppyInfolist;
use App\Filament\Resources\Puppies\Tables\PuppiesTable;
use App\Models\Puppy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PuppyResource extends Resource
{
    protected static ?string $model = Puppy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static string|\UnitEnum|null $navigationGroup = 'Customers';

    public static function form(Schema $schema): Schema
    {
        return PuppyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PuppyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PuppiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPuppies::route('/'),
            'create' => CreatePuppy::route('/create'),
            'view' => ViewPuppy::route('/{record}'),
            'edit' => EditPuppy::route('/{record}/edit'),
        ];
    }
}
