<?php

use App\Enums\DataObjectType;
use App\Enums\DtoPropertyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('data_object_properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_nullable')->default(false);
            $table->string('default')->nullable();
            $table->enum('type', DtoPropertyType::values());
            $table->foreignIdFor(\App\Models\DbDataObject::class, "data_object_id")->constrained(\App\Models\DbDataObject::getTableName())->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_object_properties');
    }
};
