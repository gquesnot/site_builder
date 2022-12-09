<?php

namespace App\Filament\Resources\DbEnumResource\Pages;

use App\Filament\Resources\DbEnumResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDbEnums extends ListRecords
{
    protected static string $resource = DbEnumResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
