<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentCaptor extends Model
{
    protected $table      = 'developments_captors';
    public $incrementing  = false; // Porque la PK es compuesta
    protected $primaryKey = ['devt_id', 'user_id'];

    protected $fillable = [
        'devt_id',
        'user_id',
        'devtUsr_is_main',
    ];

    public function development()
    {
        return $this->belongsTo(Development::class, 'devt_id', 'devt_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
