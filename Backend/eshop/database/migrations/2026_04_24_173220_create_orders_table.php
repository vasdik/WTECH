<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('order_number')->unique();
            $table->string('status')->default('pending');

            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->string('customer_email');
            $table->string('customer_phone');

            $table->string('billing_country');
            $table->string('billing_street');
            $table->string('billing_house_number');
            $table->string('billing_city');
            $table->string('billing_postal_code');

            $table->string('delivery_country');
            $table->string('delivery_street');
            $table->string('delivery_house_number');
            $table->string('delivery_city');
            $table->string('delivery_postal_code');

            $table->string('payment_code');
            $table->string('payment_label');

            $table->string('shipping_code');
            $table->string('shipping_label');
            $table->string('shipping_eta_label')->nullable();

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('vat_total', 10, 2)->default(0);

            $table->timestamp('placed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};