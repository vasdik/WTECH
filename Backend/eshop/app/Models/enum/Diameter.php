<?php

namespace App\Models\enum;


use App\Models\ProductVariant;
use App\Models\FilamentDetail;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;


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

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}