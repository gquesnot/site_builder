<?php

namespace App\Models;

use App\Enums\RelationType;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DbRelation
 *
 * @property int $id
 * @property string $name
 * @property int $model_id
 * @property int $property_id
 * @property int $other_property_id
 * @property RelationType $type
 * @property int $other_model_id
 * @property int|null $reverse_id
 * @property int|null $pivot_model_id
 * @property int|null $pivot_property_id
 * @property int|null $pivot_other_property_id
 * @property-read \App\Models\DbModel $model
 * @property-read \App\Models\DbModel $other_model
 * @property-read \App\Models\DbProperty $other_property
 * @property-read \App\Models\DbModel|null $pivot_model
 * @property-read \App\Models\DbProperty|null $pivot_other_property
 * @property-read \App\Models\DbProperty|null $pivot_property
 * @property-read \App\Models\DbProperty $property
 * @property-read DbRelation|null $reverse
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation query()
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation whereOtherModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation whereOtherPropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation wherePivotModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation wherePivotOtherPropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation wherePivotPropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation whereReverseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbRelation whereType($value)
 * @mixin \Eloquent
 */
class DbRelation extends Model
{

    public $timestamps = false;
    public $table = "db_relations";

    protected $fillable = [
        "name",
        "model_id",
        "type",
        "other_model_id",
        "property_id",
        'other_property_id',
        'reverse_id',
        "pivot_model_id",
        "pivot_property_id",
"pivot_other_property_id",
    ];

    protected $casts = [
        'type' => RelationType::class,
    ];



    public function other_model(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DbModel::class, 'other_model_id', 'id');
    }
    public function model(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DbModel::class, 'model_id', 'id');
    }

    public function pivot_model(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DbModel::class, 'pivot_model_id', 'id');
    }

    public function reverse(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DbRelation::class, 'reverse_id', 'id');
    }

    public function property(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DbProperty::class, 'property_id', 'id');
    }

    public function other_property(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DbProperty::class, 'other_property_id', 'id');
    }


    public function pivot_property(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DbProperty::class, 'pivot_property_id', 'id');
    }

    public function pivot_other_property(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DbProperty::class, 'pivot_other_property_id', 'id');
    }

    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }
}
