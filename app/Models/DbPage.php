<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DbPage
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $model_id
 * @property bool $has_view
 * @property bool $has_list
 * @property bool $with_pagination
 * @property bool $with_filter
 * @property-read \App\Models\DbModel $model
 * @method static \Illuminate\Database\Eloquent\Builder|DbPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|DbPage whereHasList($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbPage whereHasView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbPage whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbPage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbPage whereWithFilter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbPage whereWithPagination($value)
 * @mixin \Eloquent
 */
class DbPage extends Model
{

    public $timestamps = false;
    public $fillable = [
        "name",
        "model_id",
        "has_view",
        "has_list",
        "with_pagination",
        "with_filter",
    ];

    public $casts = [
        "has_view" => "boolean",
        "has_list" => "boolean",
        "with_pagination" => "boolean",
        "with_filter" => "boolean",
    ];


    public function model()
    {
        return $this->belongsTo(DbModel::class, 'model_id');
    }
}
