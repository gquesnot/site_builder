<?php

namespace App\Filament\Resources\DbEnumResource\Pages;

use App\Filament\Resources\DbEnumResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDbEnum extends CreateRecord
{
    protected static string $resource = DbEnumResource::class;
}
