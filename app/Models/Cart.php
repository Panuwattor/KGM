<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'customer_id', 'session_id', 'product_id', 'variant_id', 'quantity', 'flash_sale_price',
        'embroidery', 'embroidery_text', 'embroidery_price',
    ];

    protected $casts = [
        'embroidery'       => 'boolean',
        'embroidery_price' => 'decimal:2',
    ];

    public function product() { return $this->belongsTo(Product::class)->with('images'); }
    public function variant() { return $this->belongsTo(ProductVariant::class); }
    public function customer() { return $this->belongsTo(Customer::class); }

    public function getSubtotalAttribute(): float
    {
        $basePrice = $this->flash_sale_price ?? ($this->product->current_price ?? 0);
        $adjustment = $this->variant->price_adjustment ?? 0;
        $embroidery = $this->embroidery ? (float) $this->embroidery_price : 0;
        return ($basePrice + $adjustment + $embroidery) * $this->quantity;
    }
}
