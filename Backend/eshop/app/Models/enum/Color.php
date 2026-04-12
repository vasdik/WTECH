<?php

namespace App\Models\enum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Color extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'hex_code',
        'sort_order',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}