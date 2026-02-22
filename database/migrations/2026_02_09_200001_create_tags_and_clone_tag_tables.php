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
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('color')->nullable();
            $table->timestamps();
        });

        Schema::create('clone_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cactus_clone_id')->constrained('cactus_clones')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['cactus_clone_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clone_tag');
        Schema::dropIfExists('tags');
    }
};
