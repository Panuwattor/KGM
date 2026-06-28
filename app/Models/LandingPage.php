<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LandingPage extends Model
{
    protected $fillable = ['image_path', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function getImageUrlAttribute(): string
    {
        return Storage::disk(config('filesystems.media'))->url($this->image_path);
    }
}
