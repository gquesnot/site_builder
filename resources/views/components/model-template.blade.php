@php
    echo "<?php".PHP_EOL;
@endphp
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class {{$model->name}} extends Model
{

@if(!$model->with_timestamps)
    public $timestamps = false;
@endif

    public $table = "{{ Str::of($model->name)->snake()->plural() }}";

    protected $fillable = [
@foreach($model->properties as $property)
        '{{$property->name}}',
@endforeach
    ];

    public $casts = [
@foreach($model->properties as $property)
@if($property->cast)
        {!! $property->cast->type->get_cast_string($property) !!},
@endif
@endforeach
    ];

@foreach($model->relations as $relation)
    public function {{$relation->name}}(): \Illuminate\Database\Eloquent\Relations\{{$relation->type->get_relation_string()}}
    {
        return $this->{{$relation->type->get_relation_string()}}({{$relation->model->name}}::class, {!! $relation->type->get_relations_keys_string($relation) !!});
    }
@endforeach
}
