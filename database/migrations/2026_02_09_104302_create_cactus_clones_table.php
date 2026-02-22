<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cactus_clones', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('species')->nullable();
            $table->text('description')->nullable();
            $table->string('detail_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('species');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cactus_clones');
    }
};
