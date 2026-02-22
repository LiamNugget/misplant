<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cactus_clones', function (Blueprint $table) {
            $table->boolean('is_monstrose')->default(false)->after('species');
            $table->string('main_image_url')->nullable()->after('detail_url');
        });

        Schema::table('clone_images', function (Blueprint $table) {
            $table->string('filename')->nullable()->after('image_url');
        });

        // For crosses, we need to make father_clone_id nullable (OP crosses have no father)
        // and add the new import/inventory tracking fields.
        Schema::table('crosses', function (Blueprint $table) {
            $table->foreignId('mother_clone_id')->nullable()->change();
            $table->foreignId('father_clone_id')->nullable()->change();
            $table->string('status')->default('available')->change();
            $table->dropUnique(['code']);
            $table->string('mother_name_text')->nullable()->after('father_clone_id');
            $table->string('father_name_text')->nullable()->after('mother_name_text');
            $table->string('cross_name')->nullable()->after('father_name_text');
            $table->boolean('is_op')->default(false)->after('cross_name');
            $table->boolean('is_f2')->default(false)->after('is_op');
            $table->string('quantity_unit')->nullable()->after('seed_count_accuracy');
            $table->boolean('has_multiple_pricing')->default(false)->after('quantity_unit');
            $table->json('all_prices_json')->nullable()->after('has_multiple_pricing');
            $table->integer('initial_seed_count')->default(0)->after('all_prices_json');
            $table->integer('seeds_sold')->default(0)->after('initial_seed_count');
            $table->integer('manual_adjustment')->default(0)->after('seeds_sold');
        });
    }

    public function down(): void
    {
        Schema::table('crosses', function (Blueprint $table) {
            $table->dropColumn([
                'mother_name_text', 'father_name_text', 'cross_name',
                'is_op', 'is_f2', 'quantity_unit', 'has_multiple_pricing',
                'all_prices_json', 'initial_seed_count', 'seeds_sold', 'manual_adjustment',
            ]);
            $table->foreignId('father_clone_id')->nullable(false)->change();
        });

        Schema::table('clone_images', function (Blueprint $table) {
            $table->dropColumn('filename');
        });

        Schema::table('cactus_clones', function (Blueprint $table) {
            $table->dropColumn(['is_monstrose', 'main_image_url']);
        });
    }
};
