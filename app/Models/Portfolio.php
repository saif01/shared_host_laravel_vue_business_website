<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portfolio extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'client_name',
        'industry',
        'project_date',
        'challenge',
        'solution',
        'results',
        'featured_image',
        'images',
        'videos',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'published',
        'featured',
        'order',
    ];

    protected $casts = [
        'published' => 'boolean',
        'featured' => 'boolean',
        'order' => 'integer',
        'project_date' => 'date',
        'images' => 'array',
        'videos' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
