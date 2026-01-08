<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('world_id')->constrained('worlds')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 100);
            $table->unique(['world_id', 'name'], 'unique_tag_world');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
