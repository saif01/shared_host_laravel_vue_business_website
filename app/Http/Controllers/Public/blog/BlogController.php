<?php

namespace App\Http\Controllers\Public\blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Support\MediaPath;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::where('published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->select('id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'author_id', 'meta_title', 'meta_description', 'og_image', 'views', 'created_at', 'updated_at');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Filter by author
        if ($request->has('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'published_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSortFields = ['published_at', 'views', 'created_at', 'title'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'published_at';
        }
        
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }
        
        $query->orderBy($sortBy, $sortDirection);

        // Paginate results
        $perPage = $request->get('per_page', 12);
        $posts = $query->with([
            'author' => function ($query) {
                $query->select('id', 'name', 'email');
            },
            'categories' => function ($query) {
                $query->select('categories.id', 'categories.name', 'categories.slug', 'categories.type');
            },
            'tags' => function ($query) {
                $query->select('tags.id', 'tags.name', 'tags.slug', 'tags.type');
            }
        ])->paginate($perPage);

        $posts->getCollection()->transform(function ($post) {
            return $this->transformPostWithImages($post);
        });
        
        return response()->json($posts);
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->select('id', 'title', 'slug', 'excerpt', 'content', 'featured_image', 'published_at', 'author_id', 'meta_title', 'meta_description', 'meta_keywords', 'og_image', 'views', 'created_at', 'updated_at')
            ->with([
                'author' => function ($query) {
                    $query->select('id', 'name', 'email');
                },
                'categories' => function ($query) {
                    $query->select('categories.id', 'categories.name', 'categories.slug', 'categories.type', 'categories.description');
                },
                'tags' => function ($query) {
                    $query->select('tags.id', 'tags.name', 'tags.slug', 'tags.type');
                }
            ])
            ->firstOrFail();
        
        // Increment views
        $post->increment('views');
        
        return response()->json($this->transformPostWithImages($post));
    }

    public function categories()
    {
        $categories = Category::where('type', 'post')
            ->where('published', true)
            ->select('id', 'name', 'slug', 'description', 'image', 'order')
            ->orderBy('order')
            ->get();
        
        $categories->transform(function ($category) {
            if (!empty($category->image)) {
                $category->image = MediaPath::url($category->image);
            }
            return $category;
        });
        
        return response()->json($categories);
    }

    private function transformPostWithImages(BlogPost $post): BlogPost
    {
        if (!empty($post->featured_image)) {
            $post->featured_image = MediaPath::url($post->featured_image);
        }

        if (!empty($post->og_image)) {
            $post->og_image = MediaPath::url($post->og_image);
        }

        return $post;
    }
}

