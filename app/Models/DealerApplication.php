<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealerApplication extends Model
{
    protected $fillable = [
        'business_name', 'contact_name', 'email', 'phone',
        'address', 'province', 'business_type', 'description', 'tax_id', 'status', 'admin_note',
    ];
}
