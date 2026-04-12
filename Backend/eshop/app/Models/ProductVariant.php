<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'slug',
        'color_id',
        'weight_id',
        'diameter_id',
        'price_gross',
        'stock_qty',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'price_gross' => 'decimal:2',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function weight(): BelongsTo
    {
        return $this->belongsTo(Weight::class);
    }

    public function diameter(): BelongsTo
    {
        return $this->belongsTo(Diameter::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }
}