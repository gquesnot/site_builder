<?php

namespace App\View\Components;

use App\Models\DbEnum;
use App\Models\DbModel;
use App\Models\DbProperty;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EnumTemplate extends Component
{

    public function __construct(
        public DbEnum $enum,
    )
    {
    }

    public function render(): View
    {
        return view('components.enum-template');
    }
}
