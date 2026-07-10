<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManufacturingProductImage extends Model
{
    protected $fillable = [
        'manufacturing_product_id', 'image_path', 'sort_order',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(ManufacturingProduct::class, 'manufacturing_product_id');
    }
}
