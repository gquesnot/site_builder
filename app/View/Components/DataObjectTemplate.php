<?php

namespace App\View\Components;

use App\Models\DbDataObject;
use App\Models\DbModel;
use App\Models\DbProperty;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataObjectTemplate extends Component
{

    public function __construct(

        public DbDataObject $data_object,
    )
    {
    }

    public function render(): View
    {
        return view('components.data-object-template');
    }
}
