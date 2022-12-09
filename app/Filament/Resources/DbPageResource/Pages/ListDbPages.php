<?php

namespace App\Filament\Resources\DbPageResource\Pages;

use App\Filament\Resources\DbPageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDbPages extends ListRecords
{
    protected static string $resource = DbPageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
