<?php

namespace App\Http\Controllers\Api\announcements;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Support\MediaPath;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with('author');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by display type
        if ($request->has('display_type')) {
            $query->where('display_type', $request->display_type);
        }

        // Filter by language
        if ($request->has('language')) {
            $query->where('language', $request->language);
        }

        // Filter by date range (current active)
        if ($request->has('active_only') && $request->active_only) {
            $query->active();
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'priority');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSortFields = ['id', 'title', 'slug', 'type', 'display_type', 'is_active', 'priority', 'start_date', 'end_date', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'priority';
        }
        
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }
        
        $query->orderBy($sortBy, $sortDirection);
        
        // Secondary sort by priority if not already sorted by it
        if ($sortBy !== 'priority') {
            $query->orderBy('priority', 'desc');
        }
        
        // Tertiary sort by title
        if ($sortBy !== 'title') {
            $query->orderBy('title', 'asc');
        }

        // Paginate results
        $perPage = $request->get('per_page', 10);
        $announcements = $query->paginate($perPage);

        $announcements->getCollection()->transform(function ($announcement) {
            return $this->transformAnnouncementWithImages($announcement);
        });
        
        return response()->json($announcements);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:announcements',
            'content' => 'nullable|string',
            'short_description' => 'nullable|string',
            'type' => ['required', Rule::in(['company_news', 'offers_promotions', 'events', 'holidays', 'urgent_alerts'])],
            'display_type' => ['required', Rule::in(['slider_banner', 'popup', 'sidebar_ticker', 'page_section'])],
            'image' => 'nullable|string',
            'video' => 'nullable|string',
            'external_link' => 'nullable|url|max:255',
            'open_in_new_tab' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'priority' => 'integer|min:0|max:999',
            'language' => 'nullable|string|max:10',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'og_image' => 'nullable|string',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Announcement::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

        // Set author if not provided
        if (!isset($validated['author_id']) && auth()->check()) {
            $validated['author_id'] = auth()->id();
        }

        // Normalize image paths
        if (!empty($validated['image'])) {
            $validated['image'] = MediaPath::normalize($validated['image']);
        }
        if (!empty($validated['og_image'])) {
            $validated['og_image'] = MediaPath::normalize($validated['og_image']);
        }
        if (!empty($validated['video'])) {
            $validated['video'] = MediaPath::normalize($validated['video']);
        }

        $announcement = Announcement::create($validated);
        return response()->json($this->transformAnnouncementWithImages($announcement->load('author')), 201);
    }

    public function show($id)
    {
        // Support both id and slug for route model binding
        $announcement = Announcement::where('id', $id)->orWhere('slug', $id)->with('author')->firstOrFail();
        return response()->json($this->transformAnnouncementWithImages($announcement));
    }

    public function update(Request $request, $id)
    {
        // Support both id and slug for route model binding
        $announcement = Announcement::where('id', $id)->orWhere('slug', $id)->firstOrFail();
        
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('announcements')->ignore($announcement->id)],
            'content' => 'nullable|string',
            'short_description' => 'nullable|string',
            'type' => ['sometimes', 'required', Rule::in(['company_news', 'offers_promotions', 'events', 'holidays', 'urgent_alerts'])],
            'display_type' => ['sometimes', 'required', Rule::in(['slider_banner', 'popup', 'sidebar_ticker', 'page_section'])],
            'image' => 'nullable|string',
            'video' => 'nullable|string',
            'external_link' => 'nullable|url|max:255',
            'open_in_new_tab' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'priority' => 'integer|min:0|max:999',
            'language' => 'nullable|string|max:10',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'og_image' => 'nullable|string',
        ]);

        // Normalize image paths
        if (array_key_exists('image', $validated) && !empty($validated['image'])) {
            $validated['image'] = MediaPath::normalize($validated['image']);
        }
        if (array_key_exists('og_image', $validated) && !empty($validated['og_image'])) {
            $validated['og_image'] = MediaPath::normalize($validated['og_image']);
        }
        if (array_key_exists('video', $validated) && !empty($validated['video'])) {
            $validated['video'] = MediaPath::normalize($validated['video']);
        }

        $announcement->update($validated);
        return response()->json($this->transformAnnouncementWithImages($announcement->load('author')));
    }

    public function destroy($id)
    {
        // Support both id and slug for route model binding
        $announcement = Announcement::where('id', $id)->orWhere('slug', $id)->firstOrFail();
        $announcement->delete();
        return response()->json(['message' => 'Announcement deleted successfully']);
    }

    public function toggleStatus(Request $request, $id)
    {
        $announcement = Announcement::where('id', $id)->orWhere('slug', $id)->firstOrFail();
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();
        
        return response()->json([
            'message' => 'Announcement status updated successfully',
            'announcement' => $this->transformAnnouncementWithImages($announcement->load('author'))
        ]);
    }

    private function transformAnnouncementWithImages(Announcement $announcement): Announcement
    {
        if (!empty($announcement->image)) {
            $announcement->image = MediaPath::url($announcement->image);
        }

        if (!empty($announcement->og_image)) {
            $announcement->og_image = MediaPath::url($announcement->og_image);
        }

        if (!empty($announcement->video)) {
            $announcement->video = MediaPath::url($announcement->video);
        }

        return $announcement;
    }
}

