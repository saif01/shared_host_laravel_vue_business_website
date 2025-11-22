<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Service;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\Module;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $homePage = Page::where('page_type', 'home')->where('published', true)->first();
        
        $data = [
            'homePage' => $homePage,
            'modules' => Module::where('enabled', true)->pluck('name')->toArray(),
        ];

        // Load featured services if module is enabled
        if (Module::isEnabled('services')) {
            $data['services'] = Service::where('published', true)->orderBy('order')->limit(6)->get();
        }

        // Load featured products if module is enabled
        if (Module::isEnabled('products')) {
            $data['products'] = Product::where('published', true)->where('featured', true)->orderBy('order')->limit(6)->get();
        }

        // Load latest blog posts if module is enabled
        if (Module::isEnabled('blog')) {
            $data['posts'] = BlogPost::where('published', true)->orderBy('published_at', 'desc')->limit(3)->get();
        }

        return response()->json($data);
    }
}
