<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'event_date',
        'event_time',
        'end_date_time',
        'venue',
        'address',
        'featured_image',
        'speakers',
        'allow_registration',
        'max_attendees',
        'registered_count',
        'meta_title',
        'meta_description',
        'published',
    ];

    protected $casts = [
        'published' => 'boolean',
        'allow_registration' => 'boolean',
        'event_date' => 'date',
        'event_time' => 'datetime',
        'end_date_time' => 'datetime',
        'max_attendees' => 'integer',
        'registered_count' => 'integer',
        'speakers' => 'array',
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
