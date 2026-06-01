<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'size', 'color', 'sku', 'price_adjustment', 'stock_quantity', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean', 'price_adjustment' => 'decimal:2'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getLabelAttribute(): string
    {
        return collect([$this->size, $this->color])->filter()->implode(' / ');
    }
}
