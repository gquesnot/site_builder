@php
    echo "<?php".PHP_EOL;
@endphp
namespace App\Datas;


class {{$data_object->name}} extends \Spatie\LaravelData\Data
{

    use App\Traits\DataTrait;

    public function __construct(
@foreach($data_object->properties as $property)
    {!! $property->get_attribut_in_class_template() !!}
@endforeach
    ){}
}
