<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = ['name', 'starts_at', 'ends_at', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'starts_at' => 'datetime', 'ends_at' => 'datetime'];

    public function items() { return $this->hasMany(FlashSaleItem::class); }

    public function isActive(): bool
    {
        return $this->is_active && $this->starts_at <= now() && $this->ends_at >= now();
    }
}
