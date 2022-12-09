<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('db_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('with_timestamps')->default(true);
            $table->boolean('with_migration')->default(true);
            $table->boolean('with_seeder')->default(false);
            $table->boolean('with_factory')->default(false);
            $table->boolean('with_controller')->default(false);
            $table->boolean('with_resource')->default(false);
        });
    }




    public function down()
    {
        Schema::dropIfExists('db_models');
    }
};
