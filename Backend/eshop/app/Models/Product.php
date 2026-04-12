<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'short_description',
        'description',
        'price_gross',
        'tax_rate',
        'rating_avg',
        'rating_count',
        'stock_qty',
        'is_active',
    ];

    protected $casts = [
        'price_gross' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'rating_avg' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function defaultVariant(): HasOne
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true);
    }

    public function filamentDetail(): HasOne
    {
        return $this->hasOne(FilamentDetail::class);
    }
}