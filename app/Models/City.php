<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $primaryKey = 'city_id';

    protected $fillable = [
        'city_name',
        'city_active',
        'city_region',
        'ctry_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'ctry_id', 'ctry_id');
    }
}
