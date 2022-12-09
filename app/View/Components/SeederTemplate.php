<?php

namespace App\View\Components;

use App\Models\DbModel;
use App\Models\DbProperty;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SeederTemplate extends Component
{

    public function __construct(
        public DbModel $model,
    )
    {
    }

    public function render(): View
    {
        return view('components.seeder-template');
    }
}
