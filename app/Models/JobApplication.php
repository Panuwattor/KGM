<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'career_id', 'position_applied', 'first_name', 'last_name',
        'email', 'phone', 'birthdate', 'resume_path', 'cover_letter',
        'status', 'admin_note',
    ];
    protected $casts = ['birthdate' => 'date'];

    public function career() { return $this->belongsTo(Career::class); }
    public function getFullNameAttribute(): string { return "{$this->first_name} {$this->last_name}"; }
}
