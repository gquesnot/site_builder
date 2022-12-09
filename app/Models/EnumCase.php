<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\EnumCase
 *
 * @property int $id
 * @property string $name
 * @property bool $is_nullable
 * @property int $enum_id
 * @property-read \App\Models\DbEnum $enum
 * @method static \Illuminate\Database\Eloquent\Builder|EnumCase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnumCase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EnumCase query()
 * @method static \Illuminate\Database\Eloquent\Builder|EnumCase whereEnumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnumCase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnumCase whereIsNullable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EnumCase whereName($value)
 * @mixin \Eloquent
 */
class EnumCase extends Model
{
    public $timestamps = false;
    protected $fillable = [
        "name",
        "enum_id",
        'id',
        'is_nullable'
    ];

    public  $table = "enum_cases";

    public $casts = [
        'is_nullable' => 'boolean',
    ];

    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }

    public function enum() :BelongsTo
    {
        return $this->belongsTo(DbEnum::class, "enum_id", "id", "db_enums");
    }
}
