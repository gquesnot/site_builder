<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('db_relations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(\App\Models\DbModel::class, 'model_id')->constrained(\App\Models\DbModel::getTableName())->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\DbProperty::class, 'property_id')->constrained(\App\Models\DbProperty::getTableName())->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\DbProperty::class, 'other_property_id')->constrained(\App\Models\DbProperty::getTableName())->cascadeOnDelete();
            $table->enum('type', \App\Enums\RelationType::values());
            $table->foreignIdFor(\App\Models\DbModel::class, "other_model_id")->constrained(\App\Models\DbModel::getTableName())->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\DbRelation::class, "reverse_id")->nullable()->constrained(\App\Models\DbRelation::getTableName())->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\DbModel::class, "pivot_model_id")->nullable();
            $table->foreignIdFor(\App\Models\DbProperty::class, "pivot_property_id")->nullable();
            $table->foreignIdFor(\App\Models\DbProperty::class, "pivot_other_property_id")->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('db_relations');
    }
};
