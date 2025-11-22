<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'tag_service', 'tag_id', 'service_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'tag_product', 'tag_id', 'product_id');
    }

    public function posts()
    {
        return $this->belongsToMany(BlogPost::class, 'tag_post', 'tag_id', 'blog_post_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
