<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'image',
        'parent_id',
        'order',
        'published',
    ];

    protected $casts = [
        'published' => 'boolean',
        'order' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'category_service', 'category_id', 'service_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id');
    }

    public function posts()
    {
        return $this->belongsToMany(BlogPost::class, 'category_post', 'category_id', 'blog_post_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
