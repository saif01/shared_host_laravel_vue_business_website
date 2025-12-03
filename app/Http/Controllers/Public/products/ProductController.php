<?php

namespace App\Http\Controllers\Public\products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Support\MediaPath;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('published', true)
            ->select('id', 'title', 'slug', 'sku', 'brand', 'short_description', 'thumbnail', 'price', 'discount_percent', 'discounted_price', 'price_range', 'show_price', 'availability', 'rating', 'rating_count', 'features', 'meta_title', 'meta_description', 'og_image', 'published', 'featured', 'stock', 'order', 'created_at', 'updated_at');

        // Category filter
        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Featured filter
        if ($request->has('featured')) {
            $query->where('featured', true);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Availability filter (accepts array or single value)
        if ($request->filled('availability')) {
            $availability = is_array($request->availability) ? $request->availability : [$request->availability];
            $query->whereIn('availability', $availability);
        }

        // Brand filter (accepts array or single value)
        if ($request->filled('brand') || $request->filled('brands')) {
            $brands = $request->filled('brands') ? $request->brands : $request->brand;
            $brands = is_array($brands) ? $brands : [$brands];
            $query->whereIn('brand', $brands);
        }

        // Rating filter
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Features filter (checks if product has ANY of the requested features)
        if ($request->filled('features')) {
            $features = is_array($request->features) ? $request->features : [$request->features];
            $query->where(function ($q) use ($features) {
                foreach ($features as $feature) {
                    $q->orWhereJsonContains('features', $feature);
                }
            });
        }

        // Discount filter
        if ($request->filled('discount')) {
            if ($request->discount === 'any') {
                $query->where('discount_percent', '>', 0);
            } else {
                $query->where('discount_percent', '>=', $request->discount);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('rating_count', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Support optional pagination (if per_page is provided)
        if ($request->has('per_page') || $request->has('page')) {
            $perPage = (int) $request->get('per_page', 12);
            $perPage = max(1, min($perPage, 100)); // Limit between 1 and 100
            
            $products = $query
                ->with([
                    'categories' => function ($query) {
                        $query->select('categories.id', 'categories.name', 'categories.slug', 'categories.type');
                    },
                    'tags' => function ($query) {
                        $query->select('tags.id', 'tags.name', 'tags.slug', 'tags.type');
                    }
                ])
                ->paginate($perPage);
            
            $products->getCollection()->transform(function ($product) {
                return $this->transformProductWithImages($product);
            });
            
            return response()->json($products);
        }

        // Default: return all products (for backward compatibility and client-side filtering)
        $products = $query
            ->with([
                'categories' => function ($query) {
                    $query->select('categories.id', 'categories.name', 'categories.slug', 'categories.type');
                },
                'tags' => function ($query) {
                    $query->select('tags.id', 'tags.name', 'tags.slug', 'tags.type');
                }
            ])
            ->get();
        
        $products->transform(function ($product) {
            return $this->transformProductWithImages($product);
        });
        
        return response()->json($products);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('published', true)
            ->select('id', 'title', 'slug', 'sku', 'brand', 'short_description', 'description', 'thumbnail', 'images', 'price', 'discount_percent', 'discounted_price', 'price_range', 'show_price', 'availability', 'rating', 'rating_count', 'features', 'specifications', 'downloads', 'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'published', 'featured', 'stock', 'order', 'created_at', 'updated_at')
            ->with([
                'categories' => function ($query) {
                    $query->select('categories.id', 'categories.name', 'categories.slug', 'categories.type', 'categories.description', 'categories.image');
                },
                'tags' => function ($query) {
                    $query->select('tags.id', 'tags.name', 'tags.slug', 'tags.type');
                }
            ])
            ->firstOrFail();
        
        return response()->json($this->transformProductWithImages($product));
    }

    private function transformProductWithImages(Product $product): Product
    {
        $product->thumbnail = MediaPath::url($product->thumbnail);

        if (is_array($product->images)) {
            $product->images = array_map(function ($image) {
                return MediaPath::url($image);
            }, array_filter($product->images));
        }

        if (!empty($product->og_image)) {
            $product->og_image = MediaPath::url($product->og_image);
        }

        return $product;
    }
}
