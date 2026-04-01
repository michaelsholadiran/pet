<?php

namespace App\Filament\Resources\Puppies\Pages;

use App\Filament\Resources\Puppies\PuppyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPuppies extends ListRecords
{
    protected static string $resource = PuppyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
