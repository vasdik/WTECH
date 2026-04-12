<?php

namespace App\Models\enum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Weight extends Model
{
    protected $fillable = [
        'label',
        'grams',
        'sort_order',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}