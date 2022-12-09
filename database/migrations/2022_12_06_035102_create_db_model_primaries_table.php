<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('db_model_primaries', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\DbModel::class, "model_id")->constrained(\App\Models\DbModel::getTableName())->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\DbProperty::class, "property_id")->constrained(\App\Models\DbProperty::getTableName())->cascadeOnDelete();
            $table->primary(["model_id", "property_id"]);
        });
    }

    public function down()
    {
        Schema::dropIfExists('db_model_primaries');
    }
};
