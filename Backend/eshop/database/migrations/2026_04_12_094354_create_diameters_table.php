<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diameters', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->decimal('mm_value', 4, 2);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['label', 'mm_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diameters');
    }
};