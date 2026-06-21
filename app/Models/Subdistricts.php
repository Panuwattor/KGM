<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subdistricts extends Model
{
    protected $fillable = ['code', 'name_in_thai', 'name_in_english', 'latitude', 'longitude', 'district_id', 'zip_code'];

    public $timestamps = false;

    public function district()
    {
        return $this->belongsTo(Districts::class, 'district_id');
    }
}
