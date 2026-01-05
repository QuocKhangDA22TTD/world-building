<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('world_id')->constrained('worlds')->onDelete('cascade');
            $table->foreignId('from_entity_id')->constrained('entities')->onDelete('cascade');
            $table->foreignId('to_entity_id')->constrained('entities')->onDelete('cascade');
            $table->string('relation_type', 100);
            $table->text('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relationships');
    }
};
