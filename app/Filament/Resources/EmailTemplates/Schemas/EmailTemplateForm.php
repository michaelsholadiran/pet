<?php

namespace App\Filament\Resources\EmailTemplates\Schemas;

use App\Filament\Support\RichEditorAttachments;
use App\Models\EmailTemplate;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EmailTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Template')
                    ->schema([
                        Select::make('key')
                            ->options(EmailTemplate::keys())
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn ($record) => $record !== null),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('subject')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('variables')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Available placeholders (e.g. {{customer_name}}, {{order_id}})'),
                        Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
                Section::make('Body')
                    ->schema([
                        RichEditorAttachments::configure(
                            RichEditor::make('body_html')
                                ->required()
                                ->columnSpanFull(),
                            'rich-content/email-templates',
                            RichEditorAttachments::richToolbar(),
                        ),
                    ]),
            ]);
    }
}
