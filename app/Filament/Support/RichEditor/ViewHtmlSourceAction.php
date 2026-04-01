<?php

namespace App\Filament\Support\RichEditor;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\Width;

final class ViewHtmlSourceAction
{
    public static function make(): Action
    {
        return Action::make('viewHtmlSource')
            ->label('HTML source')
            ->modalHeading('View / edit HTML')
            ->modalDescription('Raw HTML for this editor. Saving replaces the entire document.')
            ->modalWidth(Width::FiveExtraLarge)
            ->fillForm(fn (array $arguments): array => [
                'html' => $arguments['html'] ?? '',
            ])
            ->schema([
                Textarea::make('html')
                    ->label('HTML')
                    ->rows(24)
                    ->columnSpanFull()
                    ->extraInputAttributes([
                        'class' => 'font-mono text-sm',
                        'spellcheck' => 'false',
                    ]),
            ])
            ->action(function (array $data, RichEditor $component): void {
                $html = $data['html'] ?? '';

                $component->runCommands(
                    [
                        EditorCommand::make('setContent', arguments: [$html, false]),
                    ],
                    editorSelection: null,
                );
            });
    }
}
