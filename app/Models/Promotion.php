<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name', 'description', 'type', 'min_spend', 'discount_value', 'is_active', 'starts_at', 'ends_at',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'min_spend' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
}
