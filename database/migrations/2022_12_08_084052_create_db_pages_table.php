<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('db_pages', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug');
            $table->foreignIdFor(\App\Models\DbModel::class, 'model_id');
            $table->boolean('has_view')->default(true);
            $table->boolean('has_list')->default(true);
            $table->boolean('with_pagination')->default(true);
            $table->boolean('with_filter')->default(true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('db_pages');
    }
};
