<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'icon',
        'image',
        'price_range',
        'features',
        'benefits',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'published',
        'order',
    ];

    protected $casts = [
        'published' => 'boolean',
        'order' => 'integer',
        'features' => 'array',
        'benefits' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_service', 'service_id', 'category_id')->where('type', 'service');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_service', 'service_id', 'tag_id')->where('type', 'service');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
