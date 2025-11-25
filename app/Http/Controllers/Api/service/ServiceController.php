<?php

namespace App\Http\Controllers\Api\service;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Support\MediaPath;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filter by published status
        if ($request->has('published')) {
            $query->where('published', $request->published);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'order');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        $allowedSortFields = ['id', 'title', 'slug', 'published', 'order', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'order';
        }
        
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        
        $query->orderBy($sortBy, $sortDirection);
        
        if ($sortBy !== 'title' && $sortBy !== 'order') {
            $query->orderBy('title', 'asc');
        }

        // Paginate results
        $perPage = $request->get('per_page', 10);
        $services = $query->paginate($perPage);

        $services->getCollection()->transform(function ($service) {
            return $this->transformServiceWithImages($service);
        });
        
        return response()->json($services);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|string',
            'price_range' => 'nullable|string',
            'features' => 'nullable|array',
            'benefits' => 'nullable|array',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_image' => 'nullable|string',
            'published' => 'boolean',
            'order' => 'integer',
        ]);

        // Normalize image paths
        if (!empty($validated['image'])) {
            $validated['image'] = MediaPath::normalize($validated['image']);
        }
        if (!empty($validated['og_image'])) {
            $validated['og_image'] = MediaPath::normalize($validated['og_image']);
        }

        $service = Service::create($validated);
        return response()->json($this->transformServiceWithImages($service), 201);
    }

    public function show($id)
    {
        // Support both id and slug for route model binding
        $service = Service::where('id', $id)->orWhere('slug', $id)->firstOrFail();
        return response()->json($this->transformServiceWithImages($service->load(['categories', 'tags'])));
    }

    public function update(Request $request, $id)
    {
        // Support both id and slug for route model binding
        $service = Service::where('id', $id)->orWhere('slug', $id)->firstOrFail();
        
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:services,slug,' . $service->id,
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|string',
            'price_range' => 'nullable|string',
            'features' => 'nullable|array',
            'benefits' => 'nullable|array',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_image' => 'nullable|string',
            'published' => 'boolean',
            'order' => 'integer',
        ]);

        // Normalize image paths
        if (array_key_exists('image', $validated) && !empty($validated['image'])) {
            $validated['image'] = MediaPath::normalize($validated['image']);
        }
        if (array_key_exists('og_image', $validated) && !empty($validated['og_image'])) {
            $validated['og_image'] = MediaPath::normalize($validated['og_image']);
        }

        $service->update($validated);
        return response()->json($this->transformServiceWithImages($service));
    }

    private function transformServiceWithImages(Service $service): Service
    {
        $service->image = MediaPath::url($service->image);

        if (!empty($service->og_image)) {
            $service->og_image = MediaPath::url($service->og_image);
        }

        return $service;
    }

    public function destroy($id)
    {
        // Support both id and slug for route model binding
        $service = Service::where('id', $id)->orWhere('slug', $id)->firstOrFail();
        $service->delete();
        return response()->json(['message' => 'Service deleted successfully']);
    }
}

