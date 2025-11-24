<?php

namespace App\Http\Controllers\Public\services;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('published', true)
            ->orderBy('order')
            ->with(['categories', 'tags'])
            ->get();
        
        return response()->json($services);
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)
            ->where('published', true)
            ->with(['categories', 'tags'])
            ->firstOrFail();
        
        return response()->json($service);
    }
}
