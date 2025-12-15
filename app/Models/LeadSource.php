<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{
    protected $table = 'lead_sources';
    protected $primaryKey = 'leadSou_id';

    protected $fillable = [
        'leadSou_code',
        'leadSou_name',
        'leadSou_description',
        'leadSou_active'
    ];

    protected $casts = [
        'leadSou_active' => 'boolean',
    ];
    public function leads()
    {
        return $this->hasMany(Lead::class, 'leadSou_id', 'leadSou_id');
    }
}
