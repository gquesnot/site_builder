<?php
namespace App\Enums;
use App\Traits\EnumTrait;

enum DtoPropertyType: string
{
    use EnumTrait;
    case INTEGER = 'int';
    case FLOAT = 'float';
    case BOOLEAN = 'bool';
    case ARRAY = 'array';
    case STRING = 'string';


    public function default_need_quote():bool{
        return $this == self::STRING;
    }
}
