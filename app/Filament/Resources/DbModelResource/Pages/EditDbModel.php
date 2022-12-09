<?php

namespace App\Filament\Resources\DbModelResource\Pages;

use App\Filament\Resources\DbModelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDbModel extends EditRecord
{
    protected static string $resource = DbModelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
