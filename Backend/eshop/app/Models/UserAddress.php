<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'first_name',
        'last_name',
        'phone',
        'country',
        'street',
        'house_number',
        'city',
        'postal_code',
        'is_default_billing',
        'is_default_delivery',
    ];

    protected $casts = [
        'is_default_billing' => 'boolean',
        'is_default_delivery' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}