<?php

namespace App\Models;

use App\Models\ProductVariant;
use App\Models\FilamentDetail;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;


use App\Models\enum\Color;
use App\Models\enum\Diameter;
use App\Models\enum\Weight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'product_family_id',
        'color_id',
        'weight_id',
        'diameter_id',
        'variant_label',
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

    public function family(): BelongsTo
    {
        return $this->belongsTo(ProductFamily::class, 'product_family_id');
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

    public function filamentDetail(): HasOne
    {
        return $this->hasOne(FilamentDetail::class);
    }
}