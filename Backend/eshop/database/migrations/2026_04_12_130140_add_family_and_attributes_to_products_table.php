<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('product_family_id')
                ->nullable()
                ->after('category_id')
                ->constrained('product_families')
                ->nullOnDelete();

            $table->foreignId('color_id')
                ->nullable()
                ->after('product_family_id')
                ->constrained('colors')
                ->nullOnDelete();

            $table->foreignId('weight_id')
                ->nullable()
                ->after('color_id')
                ->constrained('weights')
                ->nullOnDelete();

            $table->foreignId('diameter_id')
                ->nullable()
                ->after('weight_id')
                ->constrained('diameters')
                ->nullOnDelete();

            $table->string('variant_label')->nullable()->after('diameter_id');

            $table->index(['product_family_id', 'is_active']);
            $table->index(['color_id', 'is_active']);
            $table->index(['weight_id', 'is_active']);
            $table->index(['diameter_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['product_family_id', 'is_active']);
            $table->dropIndex(['color_id', 'is_active']);
            $table->dropIndex(['weight_id', 'is_active']);
            $table->dropIndex(['diameter_id', 'is_active']);

            $table->dropConstrainedForeignId('product_family_id');
            $table->dropConstrainedForeignId('color_id');
            $table->dropConstrainedForeignId('weight_id');
            $table->dropConstrainedForeignId('diameter_id');

            $table->dropColumn('variant_label');
        });
    }
};