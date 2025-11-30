<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'short_description',
        'type',
        'display_type',
        'image',
        'video',
        'external_link',
        'open_in_new_tab',
        'start_date',
        'end_date',
        'is_active',
        'priority',
        'language',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'author_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'priority' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($announcement) {
            if (empty($announcement->slug)) {
                $announcement->slug = Str::slug($announcement->title);
            }
            
            // Ensure unique slug
            $originalSlug = $announcement->slug;
            $count = 1;
            while (static::where('slug', $announcement->slug)->exists()) {
                $announcement->slug = $originalSlug . '-' . $count;
                $count++;
            }
        });
    }

    /**
     * Get the author that created the announcement.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope to get only active announcements.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Scope to filter by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by display type.
     */
    public function scopeOfDisplayType($query, $displayType)
    {
        return $query->where('display_type', $displayType);
    }

    /**
     * Scope to get announcements in date range.
     */
    public function scopeInDateRange($query, $startDate = null, $endDate = null)
    {
        if ($startDate) {
            $query->where(function ($q) use ($startDate) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', $startDate);
            });
        }

        if ($endDate) {
            $query->where(function ($q) use ($endDate) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $endDate);
            });
        }

        return $query;
    }

    /**
     * Check if announcement is currently active (within date range and status).
     */
    public function isCurrentlyActive()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->start_date && $this->start_date->isFuture()) {
            return false;
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get route key name for model binding.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}

