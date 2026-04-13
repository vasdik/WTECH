<?php

namespace App\Models\enum;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diameter extends Model
{
    protected $fillable = [
        'label',
        'mm_value',
        'sort_order',
    ];

    protected $casts = [
        'mm_value' => 'decimal:2',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}