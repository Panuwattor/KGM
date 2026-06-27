<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'size', 'sku', 'price_adjustment', 'stock_quantity', 'low_stock_threshold', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean', 'price_adjustment' => 'decimal:2'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getLabelAttribute(): string
    {
        return (string) $this->size;
    }

    public function isLowStock(): bool
    {
        return $this->is_active && $this->stock_quantity <= $this->low_stock_threshold;
    }
}
