<?php

namespace App\Models;

use App\Enums\CastType;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BaseCast
 *
 * @property int $id
 * @property int $property_id
 * @property CastType $type
 * @property string|null $castable_type
 * @property int|null $castable_id
 * @property-read Model|\Eloquent $castable
 * @property-read \App\Models\DbProperty|null $property
 * @method static \Illuminate\Database\Eloquent\Builder|BaseCast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseCast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseCast query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseCast whereCastableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseCast whereCastableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseCast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseCast wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseCast whereType($value)
 * @mixin \Eloquent
 */
class BaseCast extends Model
{

    public $timestamps = false;
    public $table = "base_casts";

    protected $fillable = [
        "property_id",
        "type",
        "castable_id",
        "castable_type",
        'id',
    ];

    public $casts = [
        "type" => CastType::class,
    ];


    public function property(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\DbProperty::class);
    }

    public function castable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
