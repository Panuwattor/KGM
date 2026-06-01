<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['product_id', 'customer_id', 'order_id', 'rating', 'title', 'body', 'image', 'is_approved'];
    protected $casts = ['is_approved' => 'boolean'];

    public function product() { return $this->belongsTo(Product::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
}
