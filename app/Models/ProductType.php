<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image',
        'show_on_home', 'sort_order', 'is_active', 'has_embroidery',
    ];

    protected $casts = [
        'show_on_home'   => 'boolean',
        'is_active'      => 'boolean',
        'has_embroidery' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
