<?php

namespace App\Filament\Resources\DbDataObjectResource\Pages;

use App\Filament\Resources\DbDataObjectResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDbDataObject extends EditRecord
{
    protected static string $resource = DbDataObjectResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
