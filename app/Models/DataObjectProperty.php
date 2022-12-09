<?php

namespace App\Models;

use App\Enums\DtoPropertyType;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DataObjectProperty
 *
 * @property int $id
 * @property string $name
 * @property bool $is_nullable
 * @property string|null $default
 * @property DtoPropertyType $type
 * @property int $data_object_id
 * @property-read \App\Models\DbDataObject $dataObject
 * @method static \Illuminate\Database\Eloquent\Builder|DataObjectProperty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DataObjectProperty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DataObjectProperty query()
 * @method static \Illuminate\Database\Eloquent\Builder|DataObjectProperty whereDataObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataObjectProperty whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataObjectProperty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataObjectProperty whereIsNullable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataObjectProperty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataObjectProperty whereType($value)
 * @mixin \Eloquent
 */
class DataObjectProperty extends Model
{

    public $timestamps = false;
    public $fillable = [
        "name",
        "type",
        'is_nullable',
        'data_object_id',
        'default',
        'id',
    ];

    public $table = "data_object_properties";

    public $casts = [
        'is_nullable' => 'boolean',
        'type' => DtoPropertyType::class,
    ];

    public function get_attribut_in_class_template(): string
    {
        $base = "public ";
        if ($this->is_nullable) {
            $base .= "?";
        }
        $base .= $this->type->value . " $" . $this->name;
        if ($this->is_nullable) {
            $base .= " = null";
        } elseif ($this->default) {
            $base .= " = ";
            if ($this->type->default_need_quote()) {
                $base .= "\"{$this->default}\"";
            } else {
                $base .= $this->default;
            }
        }
        return $base . ",";
    }


    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }

    public function dataObject(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DbDataObject::class, "data_object_id", "id", "db_data_objects");
    }
}
