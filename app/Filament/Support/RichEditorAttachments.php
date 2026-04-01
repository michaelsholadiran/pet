<?php

namespace App\Filament\Support;

use App\Filament\Support\RichEditor\ViewHtmlSourceAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Support\Icons\Heroicon;

final class RichEditorAttachments
{
    /**
     * Full toolbar for content fields: text styles, headings, alignment, lists, code, rules, tables, undo/redo.
     * `attachFiles` is injected next to `link` by {@see configure()}.
     * `viewHtmlSource` opens a modal of raw HTML (see {@see ViewHtmlSourceAction}).
     *
     * @return array<int, array<int, string>>
     */
    public static function richToolbar(): array
    {
        return [
            ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
            ['h2', 'h3', 'h4'],
            ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
            ['blockquote', 'bulletList', 'orderedList'],
            ['code', 'codeBlock', 'viewHtmlSource'],
            ['horizontalRule'],
            ['table'],
            ['undo', 'redo'],
        ];
    }

    /**
     * Enable image uploads for a RichEditor: public disk storage + attachFiles in the toolbar when a custom toolbar is used.
     *
     * @param  array<int, string|array<int, string>>|null  $toolbarButtons  Same shape as RichEditor::toolbarButtons(); omit to keep Filament defaults (already includes attachFiles).
     */
    public static function configure(RichEditor $editor, string $directory, ?array $toolbarButtons = null): RichEditor
    {
        if ($toolbarButtons !== null) {
            $editor->toolbarButtons(
                self::injectViewHtmlSource(self::injectAttachFiles($toolbarButtons)),
            );
        }

        return $editor
            ->registerActions([ViewHtmlSourceAction::make()])
            ->tools([
                RichEditorTool::make('viewHtmlSource')
                    ->label('HTML source')
                    ->icon(Heroicon::OutlinedCodeBracket)
                    ->action(arguments: '{ html: $getEditor().getHTML() }'),
            ])
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsDirectory($directory)
            ->fileAttachmentsVisibility('public');
    }

    /**
     * @param  array<int, string|array<int, string>>  $buttons
     * @return array<int, string|array<int, string>>
     */
    public static function injectAttachFiles(array $buttons): array
    {
        foreach ($buttons as $group) {
            if (is_array($group) && in_array('attachFiles', $group, true)) {
                return $buttons;
            }
            if ($group === 'attachFiles') {
                return $buttons;
            }
        }

        $flat = [];
        $hasNested = false;
        foreach ($buttons as $item) {
            if (is_array($item)) {
                $hasNested = true;
                break;
            }
        }

        if (! $hasNested) {
            if (in_array('attachFiles', $buttons, true)) {
                return $buttons;
            }
            $out = $buttons;
            $linkIndex = array_search('link', $out, true);
            if ($linkIndex !== false) {
                array_splice($out, (int) $linkIndex + 1, 0, ['attachFiles']);
            } else {
                $out[] = 'attachFiles';
            }

            return $out;
        }

        $out = [];
        $added = false;
        foreach ($buttons as $group) {
            if (! $added && is_array($group) && in_array('link', $group, true)) {
                $merged = $group;
                if (! in_array('attachFiles', $merged, true)) {
                    $merged[] = 'attachFiles';
                }
                $out[] = $merged;
                $added = true;
            } else {
                $out[] = $group;
            }
        }

        if (! $added) {
            $out[] = ['attachFiles'];
        }

        return $out;
    }

    /**
     * Ensures the custom `viewHtmlSource` tool appears in the toolbar (next to code tools when grouped).
     *
     * @param  array<int, string|array<int, string>>  $buttons
     * @return array<int, string|array<int, string>>
     */
    public static function injectViewHtmlSource(array $buttons): array
    {
        foreach ($buttons as $group) {
            if (is_array($group) && in_array('viewHtmlSource', $group, true)) {
                return $buttons;
            }
            if ($group === 'viewHtmlSource') {
                return $buttons;
            }
        }

        $hasNested = false;
        foreach ($buttons as $item) {
            if (is_array($item)) {
                $hasNested = true;
                break;
            }
        }

        if (! $hasNested) {
            if (in_array('viewHtmlSource', $buttons, true)) {
                return $buttons;
            }
            $codeIndex = array_search('codeBlock', $buttons, true);
            if ($codeIndex !== false) {
                $out = $buttons;
                array_splice($out, (int) $codeIndex + 1, 0, ['viewHtmlSource']);

                return $out;
            }
            $out = $buttons;
            $out[] = 'viewHtmlSource';

            return $out;
        }

        $out = [];
        $added = false;
        foreach ($buttons as $group) {
            if (! $added && is_array($group) && (in_array('codeBlock', $group, true) || in_array('code', $group, true))) {
                $merged = $group;
                if (! in_array('viewHtmlSource', $merged, true)) {
                    $merged[] = 'viewHtmlSource';
                }
                $out[] = $merged;
                $added = true;
            } else {
                $out[] = $group;
            }
        }

        if (! $added) {
            $out[] = ['viewHtmlSource'];
        }

        return $out;
    }
}
