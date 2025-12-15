<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $primaryKey = 'ctry_id';

    protected $fillable = [
        'ctry_name',
        'ctry_code',
        'ctry_active'
    ];
    protected $casts = [
        'ctry_active' => 'boolean',
    ];
}
