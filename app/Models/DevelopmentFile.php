<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentFile extends Model
{
    protected $table = 'developmentFiles';
    protected $primaryKey = 'devFile_id';

    protected $fillable = [
        'devFile_code',
        'devFile_name',
        'devFile_url',
        'devFile_isPublic',
        'devt_id',
        'docTyp_id',
        'user_id'
    ];

    public function development()
    {
        return $this->belongsTo(Development::class, 'devt_id', 'devt_id');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'docTyp_id', 'docTyp_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
