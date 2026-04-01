<?php

namespace App\Filament\Resources\Policies\Schemas;

use App\Models\Policy;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PolicyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type')
                    ->formatStateUsing(fn (string $state) => Policy::types()[$state] ?? $state)
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        Policy::TYPE_PRIVACY => 'info',
                        Policy::TYPE_RETURN => 'success',
                        Policy::TYPE_SHIPPING => 'warning',
                        default => 'gray',
                    }),
                TextEntry::make('title'),
                TextEntry::make('content')
                    ->html()
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
