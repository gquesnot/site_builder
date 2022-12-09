<?php

namespace App\Enums;

use App\Models\DbProperty;
use App\Traits\EnumTrait;
use Illuminate\Support\Facades\Log;

enum PropertyType: string
{
    use EnumTrait;

    case ID_PRIMARY = "id";
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case FOREIGN_FOR = 'foreign_for';

    case TEXT = 'text';
    case STRING = 'string';
    case DATE = 'date';
    case BOOLEAN = 'boolean';
    case ENUM = 'enum';
    case JSON = 'json';


    public function has_cast(): bool
    {
        return match ($this) {
            self::ID_PRIMARY, self::INTEGER, self::FLOAT, self::FOREIGN_FOR => false,
            default => true,
        };
    }

    public function cast_options(): array
    {
        $options = match ($this) {
            self::TEXT, self::STRING => [CastType::STRINGABLE],
            self::DATE => [CastType::CARBON],
            self::BOOLEAN => [CastType::BOOLEAN],
            self::ENUM => [CastType::ENUM],
            self::JSON => [CastType::ARRAY, CastType::COLLECTION, CastType::OBJECT, CastType::DATA_OBJECT],
            default => [],
        };
        Log::info("cast_options", collect($options)->mapWithKeys(fn($option) => [$option->value => $option->label()])->toArray());
        return collect($options)->mapWithKeys(fn($option) => [$option->value => $option->label()])->toArray();
    }


    public function default_cast_option(): ?string
    {
        return match ($this) {
            self::DATE => CastType::CARBON->value,
            self::BOOLEAN => CastType::BOOLEAN->value,
            self::ENUM => CastType::ENUM->value,
            self::JSON => CastType::ARRAY->value,
            default => null,
        };
    }


    public function can_nullable(): bool
    {
        return match ($this) {
            self::ID_PRIMARY, self::BOOLEAN => false,
            default => true,
        };
    }

    public function can_default(): bool{
        return match ($this) {
            self::ID_PRIMARY, self::FOREIGN_FOR, self::ENUM  => false,
            default => true,
        };

    }
    public function default_value() : ?string
    {
        return match ($this) {
            self::BOOLEAN => "false",
            self::JSON => "[]",
            default => null,
        };
    }


    public function can_unique(): bool
    {
        return match ($this) {
            self::JSON, self::FLOAT, self::DATE, self::BOOLEAN, self::TEXT => false,
            default => true,
        };
    }

    public function can_index(): bool
    {
        return match ($this) {
            self::JSON, self::FLOAT, self::DATE, self::BOOLEAN, self::TEXT => false,
            default => true,
        };
    }

    public function can_primary(): bool
    {
        return match ($this) {
            self::JSON, self::FLOAT, self::DATE, self::BOOLEAN, self::TEXT => false,
            default => true,
        };
    }

    public function can_auto_increment(): bool
    {
        return match ($this) {
            self::ID_PRIMARY => true,
            default => false,
        };
    }

    public function default_options() :array
    {
        return match ($this) {
            self::ID_PRIMARY => ["is_unique", "is_index", "is_primary", "is_auto_increment"],
            self::FOREIGN_FOR => ["is_index"],
            default => [],
        };
    }

    public function get_foreign_for_migration_string(DbProperty $property)
    {
        return "foreignIdFor(App\\Models\\{$property->foreign_model->name}::class, '{$property->name}')";
    }


}
