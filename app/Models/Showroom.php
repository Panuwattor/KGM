<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showroom extends Model
{
    protected $fillable = [
        'name', 'address', 'phone', 'line_id', 'email', 'open_hours',
        'map_embed_url', 'lat', 'lng', 'image', 'is_active', 'sort_order', 'is_main',
    ];
    protected $casts = ['is_active' => 'boolean', 'is_main' => 'boolean'];

    public function scopeActive($query) { return $query->where('is_active', true)->orderBy('sort_order'); }
}
