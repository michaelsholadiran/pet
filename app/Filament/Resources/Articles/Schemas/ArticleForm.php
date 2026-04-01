<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Filament\Support\RichEditorAttachments;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (?string $state, callable $set) => $set('slug', Str::slug($state ?? ''))),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                RichEditorAttachments::configure(
                    RichEditor::make('content')
                        ->columnSpanFull(),
                    'rich-content/articles',
                    RichEditorAttachments::richToolbar(),
                ),
                FileUpload::make('featured_image')
                    ->image()
                    ->directory('articles'),
                Toggle::make('is_published')
                    ->default(false),
            ]);
    }
}
