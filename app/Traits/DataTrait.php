<?php

namespace App\Traits;

use App\Casts\CastJsonData;
use Illuminate\Support\Str;

trait DataTrait
{


    static function get_labels(): array
    {
        $vars = array_keys(self::default_values());
        $labels = [];
        foreach ($vars as $var) {
            $labels[$var] = Str::of($var)->replace('_', ' ')->title()->toString();
        }
        return $labels;

    }

    public static function castUsing(array $arguments)
    {
        return new CastJsonData(static::class);
    }

    static function options_from_values(array $values): array
    {
        $options = [];
        $labels = self::get_labels();
        foreach ($values as $key => $value) {
            if (isset($labels[$key])) {
                $options[$key] = $labels[$key];
            }
        }
        return $options;
    }

    static function default_values(): array
    {
        return self::withoutMagicalCreationFrom([])->toArray();
    }

    static function options_from_type(?string $type): array
    {
        return self::options_from_values(self::default_values());
    }

    static function values_from_type(string $type): array
    {
        return self::default_values();
    }

    static function array_keys_to_array_of_true_keys(array $options)
    {
        return collect($options)->filter(function ($value, $key) {
            return $value;
        })->keys()->toArray();
    }

    public function array_of_true_keys()
    {
        return self::array_keys_to_array_of_true_keys($this->toArray());
    }

    static function from_array_true_keys(array $array): array
    {
        $options = self::default_values();
        foreach($options as $key => $value){
            $options[$key] = in_array($key, $array);

        }

        return $options;
    }


}
