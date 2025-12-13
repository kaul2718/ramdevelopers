<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    protected $table = 'lead_statuses';
    protected $primaryKey = 'leadSta_id';

    protected $fillable = [
        'leadSta_code',
        'leadSta_name',
        'leadSta_description',
        'leadSta_active'
    ];

    protected $casts = [
        'leadSta_active' => 'boolean',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'leadSta_id', 'leadSta_id');
    }
}
