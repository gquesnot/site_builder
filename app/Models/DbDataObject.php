<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DbDataObject
 *
 * @property int $id
 * @property string $name
 * @property-read \App\Models\BaseCast|null $model
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DataObjectProperty[] $properties
 * @property-read int|null $properties_count
 * @method static \Illuminate\Database\Eloquent\Builder|DbDataObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbDataObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbDataObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|DbDataObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbDataObject whereName($value)
 * @mixin \Eloquent
 */
class DbDataObject extends Model
{


    public $timestamps = false;
    public $table = "db_data_objects";

    protected $fillable = [
        'name',
    ];


    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }




    public function properties(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DataObjectProperty::class, "data_object_id", "id");
    }

    public function model(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(BaseCast::class, "castable");
    }

}
