<?php

namespace App\Filament\Pages;

use App\Enums\PropertyType;
use App\Enums\RelationType;
use App\Models\DbDataObject;
use App\Models\DbEnum;
use App\Models\DbModel;
use App\Models\DbPage;
use App\View\Components\DataObjectTemplate;
use App\View\Components\EnumTemplate;
use App\View\Components\LivewireClassListTemplate;
use App\View\Components\LivewireClassViewTemplate;
use App\View\Components\MigrationTemplate;
use App\View\Components\ModelTemplate;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Generate extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.settings';


    public string $storage = "src";

    public function generate()
    {

        // clear dest folder
        Storage::disk('local')->deleteDirectory($this->storage."/app");
        Storage::disk('local')->deleteDirectory($this->storage."/resources");
        Storage::disk('local')->deleteDirectory($this->storage."/database");
        Storage::disk('local')->deleteDirectory($this->storage."/routes");


        Storage::disk('local')->makeDirectory($this->storage . '/app/Http/Livewire');
        Storage::disk('local')->makeDirectory($this->storage . '/app/Enums');
        Storage::disk('local')->makeDirectory($this->storage . '/app/Models');
        Storage::disk('local')->makeDirectory($this->storage . '/app/Enums');
        Storage::disk('local')->makeDirectory($this->storage . '/app/Traits');
        Storage::disk('local')->makeDirectory($this->storage . '/app/Interfaces');
        Storage::disk('local')->makeDirectory($this->storage . '/app/Datas');
        Storage::disk('local')->makeDirectory($this->storage . '/database/migrations');
        Storage::disk('local')->makeDirectory($this->storage . '/database/seeders');
        Storage::disk('local')->makeDirectory($this->storage . '/database/factories');
        Storage::disk('local')->makeDirectory($this->storage . '/resources/views/livewire');
        Storage::disk('local')->makeDirectory($this->storage . '/resources/views/components');
        Storage::disk('local')->makeDirectory($this->storage . '/routes');


        Storage::disk('local')->copy('templates/Traits/EnumTrait.php', $this->storage . '/app/Traits/EnumTrait.php');
        Storage::disk('local')->copy('templates/Traits/DataTrait.php', $this->storage . '/app/Traits/DataTrait.php');
        Storage::disk('local')->copy('templates/Casts/CastJsonData.php', $this->storage . '/app/Casts/CastJsonData.php');


        DbModel::with(
            "relations",
            "properties_primary",
            "properties",
            "properties.foreign_model",
            'properties.cast.castable',
            'relations.model',
            'relations.property',
            'relations.other_model',
            'relations.other_property',
            'relations.pivot_model',
            'relations.pivot_property',
            'relations.pivot_other_property',
        )->each(function (DbModel $model) {

            $this->generate_model($model);
            if ($model->with_migration)
                $this->generate_migration($model);
//            if ($model->with_controller)
//                $this->generate_controller($model);
//            if ($model->with_factory)
//                $this->generate_factory($model);
//            if ($model->with_seeder)
//                $this->generate_seeder($model);
//            if ($model->with_resource)
//                $this->generate_resource($model);
        });

        DbEnum::with('cases')->each(function (DbEnum $enum) {
            $enum->load('cases');
            $this->generate_enum($enum);
        });

        DbDataObject::each(function (DbDataObject $data_object) {
            $data_object->load('properties');
            $this->generate_data_object($data_object);
        });
//
        DbPage::each(function (DbPage $page) {
            $page->load('model');
            $this->generate_page($page);
        });

        Notification::make()->success()->body('All files generated in storage\\app\\src')->title('Generate')->send();

    }


    public function generate_model(DbModel $model)
    {
        $view = Blade::renderComponent(new ModelTemplate($model));
        Storage::disk('local')->put($this->storage."/app/Models/" . $model->name . ".php", $view);

    }

    public function generate_migration(DbModel $model)
    {
        $view = Blade::renderComponent(new MigrationTemplate($model));
        Storage::disk('local')->put($this->storage."/database/migrations/" . date('Y_m_d_His') . "_create_" . Str::of($model->name)->snake()->plural() . "_table.php", $view);

    }

    public function generate_enum(DbEnum $enum)
    {
        $view =Blade::renderComponent(new EnumTemplate($enum));
        Storage::disk('local')->put($this->storage."/app/Enums/" . $enum->name . ".php", $view);

    }

    public function generate_data_object(DbDataObject $data_object)
    {
        $view = Blade::renderComponent(new DataObjectTemplate($data_object));

        Storage::disk('local')->put($this->storage."/app/Datas/" . $data_object->name . ".php", $view);
    }


    public function write_file(string $path, string $name, string $content)
    {
        Storage::disk($this->storage)->put($path . $name, $content);
    }


    protected function getActions(): array
    {
        return [
            Action::make('generate')->action('generate')
        ];
    }

    private function generate_controller(DbModel $model)
    {

    }

    private function generate_factory(DbModel $model)
    {

    }

    private function generate_seeder(DbModel $model)
    {

    }

    private function generate_resource(DbModel $model)
    {

    }

    private function generate_page(DbPage $page)
    {
        $view_class_list = Blade::renderComponent(new LivewireClassListTemplate($page));
        Storage::disk('local')->put($this->storage."/app/Http/Livewire/" . $page->name . "/".Str::of($page->name)->studly()->toString()."List.php", $view_class_list);

        $property_name_plural = Str::of($page->model->name)->snake()->plural()->toString();
        $property_name_singular = Str::of($page->model->name)->snake()->toString();
        $view_view_list = "<div>\n";
        $view_view_list .= "\t@foreach(\${$property_name_plural} as \${$property_name_singular})\n";
        $view_view_list .= "\t\t<div></div>\n";
        $view_view_list .= "\t@endforeach\n";
        if ($page->with_pagination){
            $view_view_list .= "\t{!! ".Str::of($page->model->name)->snake()->plural()->toString()."::links() !!}\n";
        }
        $view_view_list .= "</div>";
        Storage::disk('local')->put($this->storage."/resources/views/livewire/" . $page->slug . "/".$page->slug."-list.blade.php", $view_view_list);

        $view_class_view = Blade::renderComponent(new LivewireClassViewTemplate($page));
        Storage::disk('local')->put($this->storage."/app/Http/Livewire/" . $page->name . "/".Str::of($page->name)->studly()->toString()."View.php", $view_class_view);

        $view_view_view = "<div>\n</div>";
        Storage::disk('local')->put($this->storage."/resources/views/livewire/" . $page->slug . "/".$page->slug."-view.blade.php", $view_view_view);

    }
}
