<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'customer_phone',
        'billing_country',
        'billing_street',
        'billing_house_number',
        'billing_city',
        'billing_postal_code',
        'delivery_country',
        'delivery_street',
        'delivery_house_number',
        'delivery_city',
        'delivery_postal_code',
        'payment_code',
        'payment_label',
        'shipping_code',
        'shipping_label',
        'shipping_eta_label',
        'subtotal',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'vat_total',
        'placed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'vat_total' => 'decimal:2',
        'placed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}