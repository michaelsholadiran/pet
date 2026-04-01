<?php

namespace App\Filament\Resources\Policies;

use App\Filament\Resources\Policies\Pages\CreatePolicy;
use App\Filament\Resources\Policies\Pages\EditPolicy;
use App\Filament\Resources\Policies\Pages\ListPolicies;
use App\Filament\Resources\Policies\Pages\ViewPolicy;
use App\Filament\Resources\Policies\Schemas\PolicyForm;
use App\Filament\Resources\Policies\Schemas\PolicyInfolist;
use App\Filament\Resources\Policies\Tables\PoliciesTable;
use App\Models\Policy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PolicyResource extends Resource
{
    protected static ?string $model = Policy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Policies';

    public static function form(Schema $schema): Schema
    {
        return PolicyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PolicyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PoliciesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPolicies::route('/'),
            'create' => CreatePolicy::route('/create'),
            'view' => ViewPolicy::route('/{record}'),
            'edit' => EditPolicy::route('/{record}/edit'),
        ];
    }
}
