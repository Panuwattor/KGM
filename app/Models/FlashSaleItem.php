<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSaleItem extends Model
{
    protected $fillable = ['flash_sale_id', 'product_id', 'sale_price', 'stock_limit', 'sold_count'];
    protected $casts = ['sale_price' => 'decimal:2'];

    public function flashSale() { return $this->belongsTo(FlashSale::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
