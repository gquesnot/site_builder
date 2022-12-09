<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait EnumTrait
{
    public static function values(): array
    {
        return collect((array)static::cases())->map(fn($case) => $case->value)->toArray();
    }


    public static function select(): array
    {
        return collect(static::cases())->mapWithKeys(fn($case) => [$case->value => $case->label()])->toArray();

    }

    public function label(bool $reverse=false): string{
        return Str::of($this->value)->replace('_', ' ')->title()->__toString();
    }

}
