<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('enum_cases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_nullable')->default(false);
            $table->foreignIdFor(\App\Models\DbEnum::class, 'enum_id')->constrained(\App\Models\DbEnum::getTableName())->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('enum_cases');
    }
};
