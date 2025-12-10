<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    use HasFactory;

    protected $table      = 'developers';
    protected $primaryKey = 'devr_id';
    public $incrementing  = true;
    protected $keyType    = 'int';

    protected $fillable = [
        'devr_commercial_name',
        'devr_legal_name',
        'devr_email_contact',
        'devr_phone_contact',
        'devr_website',
        'devr_active',
        'ctry_id',
        'user_id',
    ];

    protected $casts = [
        'devr_active' => 'boolean',
    ];

    // Relación: Developer pertenece a un país
    public function country()
    {
        return $this->belongsTo(Country::class, 'ctry_id', 'ctry_id');
    }

    // Relación: Creador / Promotor (User)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id'); 
    }
    // Relación: Un Developer tiene muchos Developments
    public function developments()
    {
        return $this->hasMany(Development::class, 'devr_id', 'devr_id');
    }
}
