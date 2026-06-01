<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'name', 'type', 'value', 'minimum_order', 'maximum_discount',
        'usage_limit', 'used_count', 'per_user_limit', 'is_active', 'starts_at', 'expires_at',
    ];
    protected $casts = [
        'is_active' => 'boolean', 'value' => 'decimal:2',
        'minimum_order' => 'decimal:2', 'maximum_discount' => 'decimal:2',
        'starts_at' => 'datetime', 'expires_at' => 'datetime',
    ];

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at > now()) return false;
        if ($this->expires_at && $this->expires_at < now()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->minimum_order) return 0;
        $discount = match($this->type) {
            'percent' => $subtotal * ($this->value / 100),
            'fixed' => $this->value,
            'free_shipping' => 0,
            default => 0,
        };
        if ($this->maximum_discount) {
            $discount = min($discount, $this->maximum_discount);
        }
        return min($discount, $subtotal);
    }
}
