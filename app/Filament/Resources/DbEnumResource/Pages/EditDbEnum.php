<?php

namespace App\Filament\Resources\DbEnumResource\Pages;

use App\Filament\Resources\DbEnumResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDbEnum extends EditRecord
{
    protected static string $resource = DbEnumResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
