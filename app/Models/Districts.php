<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    protected $fillable = ['code', 'name_in_thai', 'name_in_english', 'province_id'];

    public $timestamps = false;

    public function subdistricts()
    {
        return $this->hasMany(Subdistricts::class, 'district_id');
    }

    public function province()
    {
        return $this->belongsTo(Provinces::class, 'province_id');
    }
}
