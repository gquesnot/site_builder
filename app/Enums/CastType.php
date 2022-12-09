<?php

namespace App\Enums;

use App\Models\BaseCast;
use App\Models\DbProperty;
use App\Traits\EnumTrait;

enum CastType:string
{
    use EnumTrait;

    case BOOLEAN = 'boolean';
    case ARRAY = 'array';
    case COLLECTION = 'collection';
    case OBJECT = 'object';
    case ENUM = 'enum';
    case CARBON = 'carbon';
    case STRINGABLE = 'stringable';
    case DATA_OBJECT = 'data_object';


    public function get_cast_string(DbProperty $property)
    {
        return match($this){
            CastType::BOOLEAN => "'".$property->name . "' => 'boolean'",
            CastType::ARRAY => "'".$property->name . "' => Illuminate\\Database\\Eloquent\\Casts\\AsArrayObject",
            CastType::COLLECTION => "'".$property->name . "' => Illuminate\\Database\\Eloquent\\Casts\\AsCollection",
            CastType::OBJECT => "'".$property->name . "' => 'object',",
            CastType::ENUM => "'".$property->name . "' => App\\Enums\\" .$property->cast->castable->name . "::class",
            CastType::CARBON => "'".$property->name . "' => Illuminate\\Support\\Carbon::class,",
            CastType::STRINGABLE => "'".$property->name . "' => Illuminate\\Database\\Eloquent\\Casts\\AsStringable",
            CastType::DATA_OBJECT => "'".$property->name . "' => App\\Datas\\" .$property->cast->castable->name . "::class",
        };
    }
}

