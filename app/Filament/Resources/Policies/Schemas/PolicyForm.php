<?php

namespace App\Filament\Resources\Policies\Schemas;

use App\Filament\Support\RichEditorAttachments;
use App\Models\Policy;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class PolicyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Policy details')
                    ->schema([
                        Select::make('type')
                            ->options(Policy::types())
                            ->required()
                            ->rules([fn ($livewire) => Rule::unique('policies', 'type')->ignore($livewire->record?->id)])
                            ->disabled(fn ($livewire) => $livewire->record !== null),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        RichEditorAttachments::configure(
                            RichEditor::make('content')
                                ->required()
                                ->columnSpanFull(),
                            'rich-content/policies',
                            RichEditorAttachments::richToolbar(),
                        ),
                        Toggle::make('is_active')
                            ->label('Active (visible on frontend)')
                            ->default(true),
                    ]),
            ]);
    }
}
