<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HousingType extends Model
{
    protected $table      = 'housing_types';
    protected $primaryKey = 'houTyp_id';
    public $incrementing  = false;
    protected $keyType    = 'int';

    protected $fillable = [
        'houTyp_name',
        'houTyp_code',
        'houTyp_description',
        'houTyp_active',
    ];

    protected $casts = [
        'houTyp_active' => 'boolean',
    ];

    public function developments()
    {
        return $this->hasMany(Development::class, 'houTyp_id', 'houTyp_id');
    }

    public function getRouteKeyName()
    {
        return 'houTyp_id';
    }
}
