<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalStatus extends Model
{
    protected $table = 'approval_statuses';
    protected $primaryKey = 'apvSta_id';

    protected $fillable = [
        'apvSta_code',
        'apvSta_name',
        'apvSta_description',
        'apvSta_active'
    ];

    protected $casts = [
        'apvSta_active' => 'boolean',
    ];

    public function developments()
    {
        return $this->hasMany(Development::class, 'apvSta_id', 'apvSta_id');
    }
}
