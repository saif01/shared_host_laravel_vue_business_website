<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'sku',
        'brand',
        'short_description',
        'description',
        'thumbnail',
        'images',
        'price',
        'discount_percent',
        'discounted_price',
        'price_range',
        'show_price',
        'availability',
        'rating',
        'rating_count',
        'features',
        'specifications',
        'downloads',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'published',
        'featured',
        'stock',
        'order',
    ];

    protected $casts = [
        'published' => 'boolean',
        'featured' => 'boolean',
        'show_price' => 'boolean',
        'order' => 'integer',
        'price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'rating_count' => 'integer',
        'stock' => 'integer',
        'images' => 'array',
        'features' => 'array',
        'specifications' => 'array',
        'downloads' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id')->where('type', 'product');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_product', 'product_id', 'tag_id')->where('type', 'product');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
