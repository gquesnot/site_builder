<?php

namespace App\View\Components;

use App\Models\DbModel;
use App\Models\DbPage;
use App\Models\DbProperty;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class LivewireClassListTemplate extends Component
{

    public function __construct(
        public DbPage $db_page,
    )
    {
    }

    public function get_studly_name(): string{
        return Str::of($this->db_page->name)->studly()->toString();
    }


    public function get_lower_name(): string{
        return Str::of($this->db_page->name)->snake()->toString();
    }

    public function get_property_name(): string {
        return Str::of($this->db_page->model->name)->snake()->plural()->toString();
    }

    public function render(): View
    {
        return view('components.livewire-class-list-template');
    }
}
