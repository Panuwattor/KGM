<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'customer_id', 'status', 'subtotal', 'shipping_fee',
        'discount_amount', 'vat_amount', 'total', 'needs_tax_invoice',
        'tax_id', 'tax_branch', 'tax_company_name', 'tax_address',
        'coupon_code', 'coupon_id', 'ship_name', 'ship_phone',
        'ship_address', 'ship_district', 'ship_amphoe', 'ship_province', 'ship_postcode',
        'shipping_provider', 'tracking_number', 'shipped_at', 'delivered_at',
        'payment_slip', 'payment_uploaded_at', 'payment_verified_at', 'verified_by',
        'admin_note', 'customer_note',
    ];

    protected $casts = [
        'needs_tax_invoice' => 'boolean',
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'payment_uploaded_at' => 'datetime',
        'payment_verified_at' => 'datetime',
    ];

    const STATUS_LABELS = [
        'pending_payment'   => ['label' => 'รอชำระเงิน', 'color' => 'yellow'],
        'payment_uploaded'  => ['label' => 'อัปโหลดสลิปแล้ว', 'color' => 'blue'],
        'payment_verified'  => ['label' => 'ชำระเงินแล้ว', 'color' => 'green'],
        'processing'        => ['label' => 'กำลังเตรียมส่ง', 'color' => 'indigo'],
        'shipped'           => ['label' => 'จัดส่งแล้ว', 'color' => 'purple'],
        'delivered'         => ['label' => 'ส่งถึงแล้ว', 'color' => 'emerald'],
        'cancelled'         => ['label' => 'ยกเลิก', 'color' => 'red'],
        'refunded'          => ['label' => 'คืนเงินแล้ว', 'color' => 'gray'],
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_LABELS[$this->status]['color'] ?? 'gray';
    }

    public static function generateOrderNumber(): string
    {
        $prefix = 'KGM' . date('Ymd');
        $last = static::where('order_number', 'like', $prefix . '%')->latest()->first();
        $seq = $last ? (int) substr($last->order_number, -4) + 1 : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
