<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entity_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('world_id')->constrained('worlds')->onDelete('cascade');
            $table->string('name', 100);
            $table->unique(['world_id', 'name'], 'unique_type_world');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entity_types');
    }
};
