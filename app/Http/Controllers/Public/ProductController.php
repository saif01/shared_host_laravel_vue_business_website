<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('published', true);

        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('featured')) {
            $query->where('featured', true);
        }

        $products = $query->orderBy('order')
            ->with(['categories', 'tags'])
            ->get();
        
        return response()->json($products);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('published', true)
            ->with(['categories', 'tags'])
            ->firstOrFail();
        
        return response()->json($product);
    }
}
