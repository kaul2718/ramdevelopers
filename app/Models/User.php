<?php
// filepath: app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone',
        'password',
        'idiomas',
        'usr_id_ctry',
        'profile_photo_path',
        'usr_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'usr_active' => 'boolean',
        ];
    }

     protected $casts = [
        'usr_active' => 'boolean',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'usr_id_ctry', 'ctry_id');
    }
}