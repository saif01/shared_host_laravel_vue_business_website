<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'career_id',
        'name',
        'email',
        'phone',
        'cover_letter',
        'resume_path',
        'additional_data',
        'status',
        'notes',
    ];

    protected $casts = [
        'additional_data' => 'array',
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }
}
