<?php

namespace App\Models;

use App\Models\enum\Color;
use App\Models\enum\Diameter;
use App\Models\enum\Weight;
use App\Models\enum\FilamentType;

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