<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['customer_id', 'session_id', 'product_id', 'variant_id', 'quantity'];

    public function product() { return $this->belongsTo(Product::class)->with('images'); }
    public function variant() { return $this->belongsTo(ProductVariant::class); }
    public function customer() { return $this->belongsTo(Customer::class); }

    public function getSubtotalAttribute(): float
    {
        $basePrice = $this->product->current_price ?? 0;
        $adjustment = $this->variant->price_adjustment ?? 0;
        return ($basePrice + $adjustment) * $this->quantity;
    }
}
