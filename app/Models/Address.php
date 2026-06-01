<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'customer_id', 'label', 'recipient_name', 'phone',
        'address_line1', 'address_line2', 'district', 'amphoe',
        'province', 'postcode', 'is_default',
    ];
    protected $casts = ['is_default' => 'boolean'];

    public function customer() { return $this->belongsTo(Customer::class); }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address_line1, $this->address_line2,
            'ต.' . $this->district, 'อ.' . $this->amphoe,
            'จ.' . $this->province, $this->postcode,
        ])->filter()->implode(' ');
    }
}
