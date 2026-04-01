<?php

namespace App\Filament\Resources\Policies\Tables;

use App\Models\Policy;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PoliciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->formatStateUsing(fn (string $state) => Policy::types()[$state] ?? $state)
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        Policy::TYPE_PRIVACY => 'info',
                        Policy::TYPE_RETURN => 'success',
                        Policy::TYPE_SHIPPING => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('title')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('type')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
