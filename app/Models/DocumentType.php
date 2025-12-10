<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $table = 'documentTypes';
    protected $primaryKey = 'docTyp_id';

    protected $fillable = [
        'docTyp_code',
        'docTyp_name',
        'docTyp_description',
        'docTyp_isInternal',
        'docTyp_active'
    ];

    public function developmentFiles()
    {
        return $this->hasMany(DevelopmentFile::class, 'docTyp_id', 'docTyp_id');
    }
}
