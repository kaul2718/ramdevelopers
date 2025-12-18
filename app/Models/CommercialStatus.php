<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommercialStatus extends Model
{
    protected $table      = 'commercial_statuses';
    protected $primaryKey = 'commSta_id';

    protected $fillable = [
        'commSta_name',
        'commSta_code',
        'commSta_description',
        'commSta_active',
    ];

    protected $casts = [
        'commSta_active' => 'boolean',
    ];
}
