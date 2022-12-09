<?php

namespace App\Interfaces;

interface DataFilamentInterface
{
    static function options_from_type(?string $type): array;

    static function values_from_type(string $type): array;
    static function get_labels() : array;


}
