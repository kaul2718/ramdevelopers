<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $table = 'leads';
    protected $primaryKey = 'lead_id';

    protected $fillable = [
        'lead_client_name',
        'lead_client_email',
        'lead_client_phone',
        'lead_message',
        'lead_language',
        'devt_id',
        'ctry_id',
        'user_id',
        'leadSou_id',
        'leadSta_id'
    ];

    public function development()
    {
        return $this->belongsTo(Development::class, 'devt_id', 'devt_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'ctry_id', 'ctry_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function source()
    {
        return $this->belongsTo(LeadSource::class, 'leadSou_id', 'leadSou_id');
    }

    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'leadSta_id', 'leadSta_id');
    }

    public function notes()
    {
        return $this->hasMany(LeadNote::class, 'lead_id', 'lead_id');
    }
}
