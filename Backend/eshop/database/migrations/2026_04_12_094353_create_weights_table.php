<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weights', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->unsignedInteger('grams');
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['label', 'grams']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weights');
    }
};