<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    protected $fillable = ['code', 'name_in_thai', 'name_in_english'];

    public $timestamps = false;

    public function districts()
    {
        return $this->hasMany(Districts::class, 'province_id');
    }
}
