<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'department',
        'location',
        'employment_type',
        'description',
        'responsibilities',
        'requirements',
        'benefits',
        'deadline',
        'published',
        'order',
    ];

    protected $casts = [
        'published' => 'boolean',
        'order' => 'integer',
        'deadline' => 'date',
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
