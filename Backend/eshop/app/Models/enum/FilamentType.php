<?php

namespace App\Models\enum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FilamentType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sort_order',
    ];

    public function filamentDetails(): HasMany
    {
        return $this->hasMany(FilamentDetail::class);
    }
}