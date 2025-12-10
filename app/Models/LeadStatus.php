<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    protected $table = 'leadStatuses';
    protected $primaryKey = 'leadSta_id';

    protected $fillable = [
        'leadSta_code',
        'leadSta_name',
        'leadSta_description',
        'leadSta_active'
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'leadSta_id', 'leadSta_id');
    }
}
