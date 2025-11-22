<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name',
        'label',
        'description',
        'enabled',
        'order',
        'config',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'order' => 'integer',
        'config' => 'array',
    ];

    public static function isEnabled($name)
    {
        return static::where('name', $name)->where('enabled', true)->exists();
    }
}
