<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image',
        'show_on_home', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'show_on_home' => 'boolean',
        'is_active'    => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
