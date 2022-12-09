<?php

use App\Enums\PropertyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('db_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\DbModel::class, 'model_id')->constrained(\App\Models\DbModel::getTableName())->cascadeOnDelete();
            $table->enum('type', PropertyType::values());
            $table->string('name');
            $table->json('options');
            $table->string('default')->nullable();
            $table->foreignIdFor(\App\Models\DbModel::class, "foreign_model_id")->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('db_properties');
    }
};
