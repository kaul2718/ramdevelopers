<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';
    protected $primaryKey = 'curr_id';
    public $timestamps = false;

    protected $fillable = [
        'curr_code',
        'curr_symbol',
        'curr_name',
        'curr_symbol_first',
        'curr_active'
    ];

    protected $casts = [
        'curr_symbol_first' => 'boolean',
        'curr_active' => 'boolean'
    ];

    // Relación con developments
    public function developments()
    {
        return $this->hasMany(Development::class, 'curr_id', 'curr_id');
    }
}
