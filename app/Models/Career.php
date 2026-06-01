<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = [
        'title', 'department', 'type', 'description', 'requirements',
        'benefits', 'location', 'vacancies', 'is_active', 'closes_at',
    ];
    protected $casts = ['is_active' => 'boolean', 'closes_at' => 'datetime'];

    public function applications() { return $this->hasMany(JobApplication::class); }
    public function scopeActive($query) { return $query->where('is_active', true); }
}
