<?php

namespace App\Filament\Resources\DbPageResource\Pages;

use App\Filament\Resources\DbPageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDbPage extends EditRecord
{
    protected static string $resource = DbPageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
