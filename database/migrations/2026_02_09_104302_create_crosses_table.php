<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crosses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('slug')->unique();
            $table->foreignId('mother_clone_id')->constrained('cactus_clones')->cascadeOnDelete();
            $table->foreignId('father_clone_id')->constrained('cactus_clones')->cascadeOnDelete();
            $table->decimal('price', 8, 2);
            $table->integer('seed_count')->default(0);
            $table->enum('seed_count_accuracy', ['estimated', 'approximate', 'exact'])->default('estimated');
            $table->enum('status', ['available', 'sold_out', 'coming_soon'])->default('available');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('mother_clone_id');
            $table->index('father_clone_id');
            $table->index('status');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crosses');
    }
};
