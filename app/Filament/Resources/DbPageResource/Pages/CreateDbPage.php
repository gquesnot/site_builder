<?php

namespace App\Filament\Resources\DbPageResource\Pages;

use App\Filament\Resources\DbPageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDbPage extends CreateRecord
{
    protected static string $resource = DbPageResource::class;
}
