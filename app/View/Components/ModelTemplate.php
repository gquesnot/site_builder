<?php

namespace App\View\Components;

use App\Models\DbModel;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModelTemplate extends Component
{

    public function __construct(
        public DbModel $model,
    )
    {
    }


    public function render(): View
    {
        return view('components.model-template');
    }
}
