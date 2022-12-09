@php
    echo "<?php".PHP_EOL;
@endphp

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('{{ Str::of($model->name)->snake()->plural() }}', function (Blueprint $table) {
@foreach($model->properties as $property)
{!! $property->get_migration_string() !!}
@endforeach
        });
    }




    public function down()
    {
        Schema::dropIfExists('{{ Str::of($model->name)->snake()->plural() }}');
    }
};
