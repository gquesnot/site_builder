<?php

namespace App\Models;

use App\Casts\CastableJsonData;
use App\Datas\PropertyOptions;
use App\Enums\PropertyType;
use App\Enums\RelationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;


/**
 * App\Models\DbProperty
 *
 * @property int $id
 * @property int $model_id
 * @property PropertyType $type
 * @property string $name
 * @property $options
 * @property string|null $default
 * @property int|null $foreign_model_id
 * @property-read \App\Models\BaseCast|null $cast
 * @property-read \App\Models\DbModel|null $foreign_model
 * @property-read \App\Models\DbRelation|null $relation
 * @property-read DbProperty|null $reverse
 * @method static \Illuminate\Database\Eloquent\Builder|DbProperty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbProperty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DbProperty query()
 * @method static \Illuminate\Database\Eloquent\Builder|DbProperty whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbProperty whereForeignModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbProperty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbProperty whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbProperty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbProperty whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DbProperty whereType($value)
 * @mixin \Eloquent
 */
class DbProperty extends Model
{
    public $timestamps = false;

    public $table = 'db_properties';

    protected $fillable = [
        'model_id',
        "type",
        "name",
        "options",
        'default',
        'foreign_model_id',
    ];


    protected $casts = [
        'type' => PropertyType::class,
        'options' => PropertyOptions::class,
    ];

    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }

    public function add_relation_and_reverse(array $relation): DbRelation
    {
        Arr::forget($relation, ['id', 'property_id']);
        $reverse_property_name = $relation['reverse_property_name'];
        Arr::forget($relation, ['reverse_property_name']);
        $reverse = $relation;
        $relation['type'] = RelationType::from($relation['type']);
        $reverse['type'] = $relation['type']->reverse();
        $reverse['other_id'] = $this->model_id;
        $has_already_relation = $this->relation()->exists();

        if ($has_already_relation) {

            $this->relation->update($relation);
            $this->reverse->name = $reverse_property_name;
            $this->reverse->model_id = $relation['other_id'];
            $this->reverse->relation->update($reverse);
            $this->reverse->save();
        } else {
            $this->relation()->create($relation);
            $reverse_property = DbProperty::firstOrCreate([
                'name' => $reverse_property_name,
            ], [
                'model_id' => $relation['other_id'],
                'name' => $reverse_property_name,
                'type' => $this->type,
                'options' => $this->options,
                'reverse_id' => $this->id
            ]);
            $this->update(['reverse_id' => $reverse_property->id]);
            $reverse_property->relation()->create($reverse);
        }
        return $this->relation;
    }

    public function add_enum(array $related_object)
    {
        $old_object = $this->related_object;
        if ($old_object) {
            if ($old_object::class != DbEnum::class) {
                $old_object->delete();
            } else {
                $old_object->update($related_object);
            }
        } else {
            $enum_object = DbEnum::create($related_object);
            $this->related_object()->associate($enum_object);
            $this->save();
        }
    }

    public function add_dto(array $related_object)
    {
        $old_object = $this->related_object;
        if ($old_object) {
            if ($old_object::class != DbDataObject::class) {
                $old_object->delete();
            } else {
                $old_object->update($related_object);
            }
        } else {
            $dto_object = DbDataObject::create($related_object);
            $this->related_object()->associate($dto_object);
            $this->save();
        }
    }

    public function get_migration_string(): string
    {
        $base = "\t\t\t\$table->";
        if ($this->type == PropertyType::ID_PRIMARY) {
            $base .= "id();";
            return $base;
        } elseif ($this->type == PropertyType::FOREIGN_FOR) {

            $base.= $this->type->get_foreign_for_migration_string($this);
        } elseif ($this->type == PropertyType::ENUM) {
            $base .= "enum('{$this->name}}', App\\Enums\\{$this->cast->castable->name}::values())";
        } elseif ($this->type == PropertyType::JSON) {
            $base .= "json('{$this->name}')";
        } else {
            $base .= "{$this->type->value}('{$this->name}')";
        }

        if($this->default){
            if($this->type == PropertyType::TEXT || $this->type == PropertyType::STRING || $this->type == PropertyType::JSON){
                $base .= "->default('{$this->default}')";
            }else{
                $base .= "->default({$this->default})";
            }
        }

        if($this->options->is_nullable){
            $base .= "->nullable()";
        }
        if ($this->options->is_unique) {
            $base .= "->unique()";
        }
        if ($this->options->is_index) {
            $base .= "->index()";
        }
        if ($this->options->is_auto_increment){
            $base .= "->autoIncrement()";
        }
        if ($this->options->is_constrained){
            $base .= "->constrained()";
        }
        if ($this->options->delete_on_cascade){
            $base .= "->cascadeOnDelete()";
        }
        if ($this->options->update_on_cascade){
            $base .= "->cascadeOnUpdate()";
        }


        return $base . ";";
    }


    public function foreign_model(): BelongsTo
    {
        return $this->belongsTo(DbModel::class, 'foreign_model_id');
    }


    public function relation(): HasOne
    {
        return $this->hasOne(DbRelation::class, 'property_id');
    }


    public function reverse(): HasOne
    {
        return $this->hasOne(DbProperty::class, 'other_property_id');
    }

    public function cast(): HasOne
    {

        return $this->hasOne(BaseCast::class, 'property_id', 'id');
    }


}
