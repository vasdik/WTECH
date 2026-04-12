<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FilamentDetail extends Model
{
    protected $fillable = [
        'product_id',
        'filament_type_id',
        'recommended_nozzle_temp_min',
        'recommended_nozzle_temp_max',
        'recommended_bed_temp_min',
        'recommended_bed_temp_max',
        'material_note',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function filamentType(): BelongsTo
    {
        return $this->belongsTo(FilamentType::class);
    }
}