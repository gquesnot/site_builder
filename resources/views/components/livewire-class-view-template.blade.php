@php
    echo "<?php".PHP_EOL;
@endphp
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\{{$db_page->model->name}};

class {{$get_studly_name()}}View extends Component
{

    public {{$db_page->model->name}} ${{$get_property_name()}};

    public function mount(int ${{$db_page->name}}_id)
    {
        $this->{{$get_property_name()}} = {{$db_page->model->name}}::find(${{$db_page->name}}_id);
    }

    public function render()
    {
        return view('livewire.{{$db_page->name}}.{{$get_lower_name()}}-view');
    }
}

