<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum DataObjectType: string
{
        use EnumTrait;

        case STRING = 'string';
        case INTEGER = 'integer';
        case FLOAT = 'float';
        case BOOLEAN = 'boolean';
        case ENUM = 'enum';
        case CARBON = 'carbon';
        case ARRAY = 'array';
        case COLLECTION = 'collection';
}
