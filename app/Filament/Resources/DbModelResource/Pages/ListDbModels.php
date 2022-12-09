<?php

namespace App\Filament\Resources\DbModelResource\Pages;

use App\Filament\Resources\DbModelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDbModels extends ListRecords
{
    protected static string $resource = DbModelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
