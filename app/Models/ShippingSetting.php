<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingSetting extends Model
{
    protected $fillable = ['provider_name', 'flat_rate', 'free_shipping_threshold', 'is_active', 'sort_order'];
    protected $casts = ['is_active' => 'boolean', 'flat_rate' => 'decimal:2', 'free_shipping_threshold' => 'decimal:2'];
}
