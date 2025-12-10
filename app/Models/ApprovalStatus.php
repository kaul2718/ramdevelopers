<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalStatus extends Model
{
    protected $table      = 'approvalStatuses';
    protected $primaryKey = 'apvSta_id';

    protected $fillable = [
        'apvSta_name',
        'apvSta_code',
        'apvSta_description',
        'apvSta_active',
    ];
}
