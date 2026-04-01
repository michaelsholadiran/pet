<?php

namespace App\Filament\Resources\Puppies\Pages;

use App\Filament\Resources\Puppies\PuppyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPuppy extends ViewRecord
{
    protected static string $resource = PuppyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
