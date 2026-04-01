<?php

namespace App\Filament\Resources\QuizResults;

use App\Filament\Resources\QuizResults\Pages\CreateQuizResult;
use App\Filament\Resources\QuizResults\Pages\EditQuizResult;
use App\Filament\Resources\QuizResults\Pages\ListQuizResults;
use App\Filament\Resources\QuizResults\Schemas\QuizResultForm;
use App\Filament\Resources\QuizResults\Tables\QuizResultsTable;
use App\Models\QuizResult;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuizResultResource extends Resource
{
    protected static ?string $model = QuizResult::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|\UnitEnum|null $navigationGroup = 'Quiz';

    public static function form(Schema $schema): Schema
    {
        return QuizResultForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuizResultsTable::configure($table);
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
            'index' => ListQuizResults::route('/'),
            'create' => CreateQuizResult::route('/create'),
            'edit' => EditQuizResult::route('/{record}/edit'),
        ];
    }
}
