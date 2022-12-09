@php
    echo "<?php".PHP_EOL;
@endphp

namespace App\Enums;

use App\Traits\EnumTrait;

enum {{$enum->name}}: string
{
    use EnumTrait;

@foreach($enum->cases as $case)
    case {{$case->name}} = '{{Str::of($case->name)->lower()}}';
@endforeach
}
