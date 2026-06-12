<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = ['min_qty', 'max_qty', 'price', 'is_active'];

    protected $casts = ['price' => 'float', 'is_active' => 'boolean'];

    public static function feeForQty(int $qty): float
    {
        $rate = static::where('is_active', true)
            ->where('min_qty', '<=', $qty)
            ->where(fn($q) => $q->whereNull('max_qty')->orWhere('max_qty', '>=', $qty))
            ->orderByDesc('min_qty')
            ->first();

        return $rate ? $rate->price : 0;
    }
}
