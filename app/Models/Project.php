<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'short_description',
        'client',
        'year',
        'thumbnail',
        'budget',
        'duration',
        'accent_theme_color',
        'gallery',
        'services_list',
        'metrics',
        'seo_metadata',
        'status',
        'is_featured',
        'sort_order',
        'stack',
        'live_url',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'services_list' => 'array',
            'metrics' => 'array',
            'seo_metadata' => 'array',
            'is_featured' => 'boolean',
            'year' => 'integer',
            'budget' => 'integer',
            'duration' => 'integer',
            'sort_order' => 'integer',
            'stack' => 'array',
        ];
    }
}