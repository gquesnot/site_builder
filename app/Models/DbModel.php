<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * App\Models\DbModel
 *
 * @property int $id
 * @property string $name
 * @property bool $with_timestamps
 * @property bool $with_migration
 * @property bool $with_seeder
 * @property bool $with_factory
 * @property bool $with_controller
 * @property bool $with_resource
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DbModelPrimary[] $primaries
 * @property-read int|null $primaries_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DbProperty[] $properties
 * @property-read int|null $properties_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DbProperty[] $properties_primary
 * @property-read int|null $properties_primary_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DbRelation[] $relations
 * @property-read int|null $relations_count
 * @method static \Illuminate\Database\Eloquent\Builder|DbModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|DbModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbModel whereWithController($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbModel whereWithFactory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbModel whereWithMigration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbModel whereWithResource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbModel whereWithSeeder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbModel whereWithTimestamps($value)
 * @mixin \Eloquent
 */
class DbModel extends BaseModel
{
    public $timestamps = false;
    public $table = "db_models";

    protected $fillable = [
        "name",
        "with_timestamps",
        "with_migration",
        "with_seeder",
        "with_factory",
        "with_controller",
        "with_resource",

    ];

    public $casts = [
        "with_timestamps" => "boolean",
        "with_migration" => "boolean",
        "with_seeder" => "boolean",
        "with_factory" => "boolean",
        "with_controller" => "boolean",
        "with_resource" => "boolean",
    ];

    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }

    public function primaries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DbModelPrimary::class, "model_id", "id");
    }

    public function properties_primary(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(DbProperty::class, DbModelPrimary::class, "model_id", "property_id");
    }


    public function relations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\DbRelation::class, 'model_id');
    }

    public function properties(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DbProperty::class, 'model_id', 'id');
    }


}
