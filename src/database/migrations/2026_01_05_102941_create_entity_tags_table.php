<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entity_tags', function (Blueprint $table) {
            $table->foreignId('entity_id')->constrained('entities')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->primary(['entity_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entity_tags');
    }
};
