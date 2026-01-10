<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Development extends Model
{
    protected $primaryKey = 'devt_id';

    protected $fillable = [
        'user_id',
        'devr_id',
        'ctry_id',
        'city_id',
        'apvSta_id',
        'busiSta_id',
        'commSta_id',
        'houTyp_id',
        'curr_id',
        'devt_title',
        'devt_slug',
        'devt_address',
        'devt_short_description',
        'devt_long_description',
        'devt_price_from',
        'devt_price_to',
        'devt_delivery_year',
        'devt_estimated_profit',
        'devt_estimated_profit_is_percentage',
        'devt_is_featured',
        'devt_active',
        'devt_storage_rooms',
        'devt_parking_spaces',
        'devt_terraces',
        'devt_swimming_pools',
        'devt_children_areas',
        'devt_green_zones',
        'devt_elevators',
        'devt_golf_courses',
        'devt_bedrooms',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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

    public function commercialStatus()
    {
        return $this->belongsTo(CommercialStatus::class, 'commSta_id');
    }

    public function housingType()
    {
        return $this->belongsTo(HousingType::class, 'houTyp_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'curr_id');
    }

    // Relación: Un Development tiene muchos DevelopmentFiles
    public function developmentFiles()
    {
        return $this->hasMany(DevelopmentFile::class, 'devt_id', 'devt_id');
    }

    // Alias para developmentFiles
    public function files()
    {
        return $this->developmentFiles();
    }

    // Relación: Un Development tiene muchas DevelopmentImages
    public function developmentImages()
    {
        return $this->hasMany(DevelopmentImage::class, 'devt_id', 'devt_id');
    }

    // Alias para developmentImages
    public function images()
    {
        return $this->developmentImages();
    }

    // Relación: Un Development tiene muchos Captadores (Usuarios)
    public function captors()
    {
        return $this->belongsToMany(
            User::class,
            'developments_captors',
            'devt_id',
            'user_id'
        )->withPivot('devtUsr_is_main')->withTimestamps();
    }

    // Relación: Un Development tiene muchos DevelopmentCaptors
    public function developmentCaptors()
    {
        return $this->hasMany(DevelopmentCaptor::class, 'devt_id', 'devt_id');
    }
}