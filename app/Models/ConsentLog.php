<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsentLog extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'session_id', 'ip_address',
        'analytics_consent', 'marketing_consent', 'necessary_consent', 'consent_version',
    ];
    protected $casts = [
        'analytics_consent' => 'boolean',
        'marketing_consent' => 'boolean',
        'necessary_consent' => 'boolean',
        'consented_at' => 'datetime',
    ];

    const CREATED_AT = 'consented_at';
    const UPDATED_AT = null;
}
