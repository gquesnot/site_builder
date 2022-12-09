<?php

namespace App\Enums;


use App\Models\DbRelation;
use App\Traits\EnumTrait;

enum RelationType: string
{
    use EnumTrait;

    case ONE_TO_ONE = "one_to_one";
    case ONE_TO_ONE_REVERSE = "one_to_one_reverse";
    case MANY_TO_ONE = "many_to_one";
    case ONE_TO_MANY = "one_to_many";
    case HAS_ONE_THROUGH = "has_one_through";
    case HAS_MANY_THROUGH = "has_many_through";
    case MANY_TO_MANY = "many_to_many";


    public function reverse(): RelationType
    {
        return match ($this) {
            self::ONE_TO_ONE => self::ONE_TO_ONE_REVERSE,
            self::ONE_TO_ONE_REVERSE => self::ONE_TO_ONE,
            self::MANY_TO_ONE => self::ONE_TO_MANY,
            self::ONE_TO_MANY => self::MANY_TO_ONE,
            self::HAS_ONE_THROUGH => self::HAS_ONE_THROUGH,
            self::HAS_MANY_THROUGH => self::HAS_MANY_THROUGH,
            self::MANY_TO_MANY => self::MANY_TO_MANY,
        };
    }

    public function is_plural(): bool
    {
        return match ($this) {
            self::ONE_TO_MANY, self::MANY_TO_MANY, self::HAS_MANY_THROUGH => true,
            default => false,
        };
    }

    public function is_reverse(): bool
    {
        return match ($this) {
            self::ONE_TO_ONE_REVERSE, self::MANY_TO_ONE => true,
            default => false,
        };
    }


    public static function select(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label()
        ])->toArray();
    }

//    public function label(string $from_property_name,string $from, string $to_property_name,string $to): string
//    {
//
//        return ucwords(Str::of($this->value)->replace('_', ' ')). " | " . $this->relation_to_string($from_property_name, $from,  $to_property_name, $to);
//    }

    public function relation_to_string(string $from_property_name, string $from, string $to_property_name, string $to): string
    {
        return "$from_property_name: " . self::from_relation($to) . " => $to_property_name: " . self::reverse()->from_relation($from);
    }

    public function can_be_nullable(): bool
    {
        return match ($this) {
            self::ONE_TO_ONE, self::ONE_TO_ONE_REVERSE, self::MANY_TO_ONE, self::HAS_ONE_THROUGH => false,
            self::ONE_TO_MANY, self::HAS_MANY_THROUGH, self::MANY_TO_MANY => true,
        };
    }


    public function className(): string
    {
        return match ($this) {
            self::ONE_TO_ONE, self::ONE_TO_ONE_REVERSE, self::MANY_TO_ONE => "BelongsTo",
            self::ONE_TO_MANY => "HasMany",
            self::HAS_ONE_THROUGH => "HasOneThrough",
            self::HAS_MANY_THROUGH => "HasManyThrough",
            self::MANY_TO_MANY => "BelongsToMany",
        };
    }


    public function from_relation(string $model): string
    {
        return match ($this) {
            self::ONE_TO_ONE => "hasOne $model",
            self::ONE_TO_MANY => "hasMany $model",
            self::ONE_TO_ONE_REVERSE, self::MANY_TO_ONE => "belongsTo $model",
            self::MANY_TO_MANY => "belongsToMany $model",
            self::HAS_ONE_THROUGH => "hasOne $model Through pivot",
            self::HAS_MANY_THROUGH => "hasMany $model Through pivot",
        };
    }

    public function get_model_relation_string(DbRelation $relation): string
    {
        $base = "\$this->{$this->get_relation_string()}({$relation->model->name}::class,";
        if ($this != self::MANY_TO_MANY) {
            if ($this->has_pivot()) {
                $base .= "{$relation->pivot_model->name}::class,";
                $base .= "'{$relation->pivot_property->name}',";
                $base .= "'{$relation->pivot_other_property->name}',";
                $base .= "'{$relation->property->name}',";
                $base .= "'{$relation->other_property->name}'";
            } else {
                if ($this->is_reverse()) {
                    $base .= "'{$relation->property->name}',";
                    $base .= "'{$relation->other_property->name}'";


                } else {
                    $base .= "'{$relation->other_property->name}',";
                    $base .= "'{$relation->property->name}'";
                }
            }
        } else {
            $base .= ")->using({$relation->pivot_model->name}::class)";
            $base .= "->withPivot('{$relation->pivot_property->name}', '{$relation->pivot_other_property->name}'";
        }
        $base .= ");";
        return $base;
    }

    public function get_relations_keys_string(DbRelation $relation): string
    {
        $result = "";
        if ($this != self::MANY_TO_MANY) {
            if ($this->has_pivot()) {
                $result .= "{$relation->pivot_model->name}::class,";
                $result .= "'{$relation->pivot_property->name}',";
                $result .= "'{$relation->pivot_other_property->name}',";
                $result .= "'{$relation->property->name}',";
                $result .= "'{$relation->other_property->name}'";
            } else {
                if ($this->is_reverse()) {
                    $result .= "'{$relation->property->name}',";
                    $result .= "'{$relation->other_property->name}'";


                } else {
                    $result .= "'{$relation->other_property->name}',";
                    $result .= "'{$relation->property->name}'";
                }
            }
        } else {
            $result .= ")->using({$relation->pivot_model->name}::class)";
            $result .= "->withPivot('{$relation->pivot_property->name}', '{$relation->pivot_other_property->name}'";
        }
        return $result;
    }


    public function get_relation_string(): string
    {
        return match ($this) {
            self::ONE_TO_ONE => "hasOne",
            self::ONE_TO_MANY => "hasMany",
            self::ONE_TO_ONE_REVERSE, self::MANY_TO_ONE => "belongsTo",
            self::MANY_TO_MANY => "belongsToMany",
            self::HAS_ONE_THROUGH => "hasOneThrough",
            self::HAS_MANY_THROUGH => "hasManyThrough",
        };
    }

    public function get_migration_string(DbRelation $relation): string
    {
    }

    public function get_full_path_relation_string(): string
    {
        return "\\Illuminate\\Database\\Eloquent\\Relations\\" . $this->get_relation_string();
    }

    private function has_pivot()
    {
        return match ($this) {
            self::HAS_ONE_THROUGH, self::HAS_MANY_THROUGH, self::MANY_TO_MANY => true,
            default => false,
        };
    }

}
