<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'name', 'image', 'type', 'value', 'minimum_order', 'maximum_discount',
        'usage_limit', 'used_count', 'per_user_limit', 'is_active', 'is_public', 'is_stackable',
        'starts_at', 'expires_at',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'is_public'        => 'boolean',
        'is_stackable'     => 'boolean',
        'value'            => 'decimal:2',
        'minimum_order'    => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'starts_at'        => 'datetime',
        'expires_at'       => 'datetime',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'coupon_products');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'coupon_categories');
    }

    public function customerCoupons(): HasMany
    {
        return $this->hasMany(CustomerCoupon::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at > now()) return false;
        if ($this->expires_at && $this->expires_at < now()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        return true;
    }

    public function isValidForCustomer(int $customerId): bool
    {
        if (!$this->isValid()) return false;
        if ($this->per_user_limit) {
            $usedCount = Order::where('customer_id', $customerId)
                ->where('coupon_id', $this->id)
                ->count();
            if ($usedCount >= $this->per_user_limit) return false;
        }
        return true;
    }

    /**
     * Calculate discount amount.
     * For product/category scoped coupons, discount applies only to eligible items.
     * minimum_order is always checked against the full cart subtotal.
     */
    public function calculateDiscount(float $subtotal, ?Collection $cartItems = null): float
    {
        if ($subtotal < $this->minimum_order) return 0;
        if ($this->type === 'free_shipping') return 0;

        $eligibleSubtotal = $subtotal;

        if ($cartItems && $cartItems->isNotEmpty()) {
            $productIds  = $this->relationLoaded('products')  ? $this->products->pluck('id')  : collect();
            $categoryIds = $this->relationLoaded('categories') ? $this->categories->pluck('id') : collect();

            if ($productIds->isNotEmpty() || $categoryIds->isNotEmpty()) {
                $eligibleSubtotal = $cartItems->filter(function ($item) use ($productIds, $categoryIds) {
                    if ($productIds->isNotEmpty() && $productIds->contains($item->product_id)) return true;
                    if ($categoryIds->isNotEmpty() && $categoryIds->contains($item->product?->category_id)) return true;
                    return false;
                })->sum('subtotal');
            }
        }

        if ($eligibleSubtotal <= 0) return 0;

        $discount = match ($this->type) {
            'percent' => $eligibleSubtotal * ((float) $this->value / 100),
            'fixed'   => (float) $this->value,
            default   => 0,
        };

        if ($this->maximum_discount) {
            $discount = min($discount, (float) $this->maximum_discount);
        }

        return min($discount, $eligibleSubtotal);
    }

    public function hasProductScope(): bool
    {
        if (!$this->relationLoaded('products') || !$this->relationLoaded('categories')) return false;
        return $this->products->isNotEmpty() || $this->categories->isNotEmpty();
    }

    public function scopePubliclyVisible($query)
    {
        return $query->where('is_public', true)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'percent'      => 'ลด ' . (float) $this->value . '%'
                              . ($this->maximum_discount ? ' (สูงสุด ฿' . number_format((float) $this->maximum_discount, 0) . ')' : ''),
            'fixed'        => 'ลด ฿' . number_format((float) $this->value, 0),
            'free_shipping'=> 'จัดส่งฟรี',
            default        => $this->type,
        };
    }
}
