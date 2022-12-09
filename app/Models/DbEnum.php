<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DbEnum
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EnumCase[] $cases
 * @property-read int|null $cases_count
 * @property-read \App\Models\BaseCast|null $model
 * @method static \Illuminate\Database\Eloquent\Builder|DbEnum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbEnum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbEnum query()
 * @method static \Illuminate\Database\Eloquent\Builder|DbEnum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbEnum whereName($value)
 * @mixin \Eloquent
 */
class DbEnum extends Model
{
    public $timestamps = false;
    public $table = "db_enums";

    protected $fillable = [
        'name',
    ];


    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }

    public function cases(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EnumCase::class, "enum_id", "id");
    }


    public function model(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(BaseCast::class, "castable");
    }
}
