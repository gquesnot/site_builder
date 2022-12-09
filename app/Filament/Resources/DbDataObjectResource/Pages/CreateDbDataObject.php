<?php

namespace App\Filament\Resources\DbDataObjectResource\Pages;

use App\Filament\Resources\DbDataObjectResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDbDataObject extends CreateRecord
{
    protected static string $resource = DbDataObjectResource::class;
}
