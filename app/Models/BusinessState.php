<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessState extends Model
{
    protected $table      = 'businessState';
    protected $primaryKey = 'busiSta_id';

    protected $fillable = [
        'busiSta_name',
        'busiSta_code',
        'busiSta_description',
        'busiSta_active',
    ];
}
