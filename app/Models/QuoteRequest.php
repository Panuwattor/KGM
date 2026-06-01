<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    protected $fillable = [
        'customer_id', 'company_name', 'contact_name', 'email', 'phone',
        'product_details', 'quantity', 'notes', 'attachment', 'status',
        'quoted_price', 'quote_pdf', 'admin_note', 'quoted_at',
    ];
    protected $casts = ['quoted_price' => 'decimal:2', 'quoted_at' => 'datetime'];

    public function customer() { return $this->belongsTo(Customer::class); }
}
