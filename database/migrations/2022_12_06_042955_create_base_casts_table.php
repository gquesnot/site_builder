<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('base_casts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\DbProperty::class, 'property_id');
            $table->enum('type', \App\Enums\CastType::values());
            $table->nullableMorphs('castable');
        });
    }

    public function down()
    {
        Schema::dropIfExists('base_casts');
    }
};
