<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('label')->nullable();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();

            $table->string('country');
            $table->string('street');
            $table->string('house_number');
            $table->string('city');
            $table->string('postal_code');

            $table->boolean('is_default_billing')->default(false);
            $table->boolean('is_default_delivery')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};