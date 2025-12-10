<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Development extends Model
{
    protected $primaryKey = 'devt_id';

    protected $fillable = [
        'devr_id',
        'ctry_id',
        'city_id',
        'apvSta_id',
        'busiSta_id',
        'devt_title',
        'devt_slug',
        'devt_address',
        'devt_asset_type',
        'devt_short_description',
        'devt_long_description',
        'devt_price_from',
        'devt_price_to',
        'devt_delivery_year',
        'devt_estimated_profit',
        'devt_is_featured',
    ];

    public function developer()
    {
        return $this->belongsTo(Developer::class, 'devr_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'ctry_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function approvalStatus()
    {
        return $this->belongsTo(ApprovalStatus::class, 'apvSta_id');
    }

    public function businessStatus()
    {
        return $this->belongsTo(BusinessState::class, 'busiSta_id');
    }
}
