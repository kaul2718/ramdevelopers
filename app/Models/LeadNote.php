<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    use HasFactory;

    protected $table = 'leadNotes';
    protected $primaryKey = 'leadNot_id';

    protected $fillable = [
        'leadNot_title',
        'leadNot_description',
        'leadNot_date',
        'leadNot_active',
        'lead_id',
        'user_id'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
