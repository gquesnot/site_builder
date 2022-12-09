@php
    echo "<?php".PHP_EOL;
@endphp
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\{{$db_page->model->name}};
@if($db_page->with_pagination)
use Livewire\WithPagination;
@endif

class {{$get_studly_name()}}List extends Component
{
@if($db_page->with_pagination)
    use WithPagination;
@endif
@if(!$db_page->with_pagination)
    public {{$db_page->model->name}} ${{$get_property_name()}};
@endif
@if($db_page->with_filter)
    public $listeners = ['filtersUpdated'];
    public array $filters = [];
    public string $search = "";
@endif

    public function mount()
    {
@if(!$db_page->with_pagination)
        $this->{{$get_property_name()}} = {{$db_page->model->name}}::all();
@endif
    }

@if($db_page->with_filter)
    public function filtersUpdated($filters)
    {
        $this->filters = $filters;
    }
@endif

    public function getQuery(){
        $query = {{$db_page->model->name}}::query();
@if($db_page->with_filter)
        // TODO: Add filters
@endif
        return $query;
    }

    public function render()
    {
@if($db_page->with_pagination)
        return view('livewire.{{$db_page->name}}.{{$get_lower_name()}}-list', ['{{$get_property_name()}}' => $this->getQuery()->paginate($this->perPage)]);
@else
        return view('livewire.{{$db_page->name}}.{{$get_lower_name()}}-list');
@endif
     }

}

