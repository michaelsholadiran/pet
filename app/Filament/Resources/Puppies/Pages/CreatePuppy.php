<?php

namespace App\Filament\Resources\Puppies\Pages;

use App\Filament\Resources\Puppies\PuppyResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePuppy extends CreateRecord
{
    protected static string $resource = PuppyResource::class;
}
