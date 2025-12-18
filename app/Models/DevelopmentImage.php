<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentImage extends Model
{
    protected $table = 'development_images';
    protected $primaryKey = 'devImg_id';

    protected $fillable = [
        'devImg_title',
        'devImg_url',
        'devImg_is_cover',
        'devImg_isCover',
        'devImg_order',
        'devt_id'
    ];

    public function development()
    {
        return $this->belongsTo(Development::class, 'devt_id', 'devt_id');
    }
}
