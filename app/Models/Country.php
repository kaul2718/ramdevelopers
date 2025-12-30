<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Get the cities for the country.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'ctry_id', 'ctry_id');
    }
}
