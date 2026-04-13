<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filament_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->unique()
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('filament_type_id')
                ->constrained('filament_types')
                ->restrictOnDelete();

            $table->unsignedSmallInteger('recommended_nozzle_temp_min')->nullable();
            $table->unsignedSmallInteger('recommended_nozzle_temp_max')->nullable();
            $table->unsignedSmallInteger('recommended_bed_temp_min')->nullable();
            $table->unsignedSmallInteger('recommended_bed_temp_max')->nullable();

            $table->text('material_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filament_details');
    }
};