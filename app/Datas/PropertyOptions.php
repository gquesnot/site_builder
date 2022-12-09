<?php

namespace App\Datas;

use App\Enums\PropertyType;
use App\Interfaces\DataFilamentInterface;
use App\Traits\DataTrait;

class PropertyOptions extends \Spatie\LaravelData\Data implements DataFilamentInterface
{

    use DataTrait;

    public function __construct(
        public bool  $is_nullable = true,
        public bool  $is_unique = false,
        public bool  $is_index = false,
        public ?bool $is_auto_increment = false,
        public ?bool $is_constrained = false,
        public ?bool $delete_on_cascade = false,
        public ?bool $update_on_cascade = false,
    )
    {
    }


    public static function options_from_type(?string $type): array
    {
        $base = [];
        if (!$type) return $base;
        $type = PropertyType::from($type);
        if ($type->can_nullable()) {
            $base['is_nullable'] = "Is Nullable";
        }
        if ($type->can_unique()) {
            $base['is_unique'] = "Is Unique";
        }
        if ($type->can_index()) {
            $base['is_index'] = "Is Index";
        }
        if ($type->can_auto_increment()) {
            $base['is_auto_increment'] = "Is Auto Increment";
        }

        if ($type == PropertyType::FOREIGN_FOR){
            $base['is_constrained'] = "Is Constrained";
            $base['delete_on_cascade'] = "Delete on Cascade";
            $base['update_on_cascade'] = "Update on Cascade";
        }


        // TODO: default value

        return $base;
    }



    public static function values_from_type(string $type): array
    {
        $type = PropertyType::from($type);

        return match ($type) {
            PropertyType::ID_PRIMARY => self::id_values(),
            default => self::default_values(),
        };
    }


    static function id_values()
    {
        return [
            "is_nullable" => false,
            "is_unique" => true,
            "is_index" => true,
            "is_primary" => true,
            "is_auto_increment" => true,
        ];
    }

    static function relation_values(): array
    {
        return [
            "is_nullable" => false,
            "is_unique" => false,
            "is_index" => true,
            "is_primary" => false,
        ];
    }

}
