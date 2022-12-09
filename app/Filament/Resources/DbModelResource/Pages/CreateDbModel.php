<?php

namespace App\Filament\Resources\DbModelResource\Pages;

use App\Datas\PropertyOptions;
use App\Enums\PropertyType;
use App\Filament\Resources\DbModelResource;
use App\Models\DbModel;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDbModel extends CreateRecord
{
    protected static string $resource = DbModelResource::class;

    protected function handleRecordCreation(array $data): DbModel
    {
        $record = static::getModel()::create($data);
        $record->properties()->create([
            "name" => "id",
            "options" => PropertyOptions::withoutMagicalCreationFrom(PropertyOptions::values_from_type("id")),
            "type" => PropertyType::ID_PRIMARY,
        ]);
        return $record;
    }

}
