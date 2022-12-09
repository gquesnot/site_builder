<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DbModelPrimary
 *
 * @property int $model_id
 * @property int $property_id
 * @property-read \App\Models\DbModel $model
 * @property-read \App\Models\DbProperty $property
 * @method static \Illuminate\Database\Eloquent\Builder|DbModelPrimary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbModelPrimary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbModelPrimary query()
 * @method static \Illuminate\Database\Eloquent\Builder|DbModelPrimary whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbModelPrimary wherePropertyId($value)
 * @mixin \Eloquent
 */
class DbModelPrimary extends Model
{

    public $timestamps = false;
    protected $primaryKey = ['model_id', 'property_id'];
    public $incrementing = false;
    protected $fillable = [
        "model_id",
        "property_id",
    ];

    public function model(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DbModel::class, "model_id", "id");
    }

    public function property(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DbProperty::class, "property_id", "id");
    }
}
