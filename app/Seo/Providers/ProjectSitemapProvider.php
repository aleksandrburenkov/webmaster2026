<?php

namespace App\Seo\Providers;

use App\Models\Project;
use App\Seo\SitemapProvider;
use Throwable;

class ProjectSitemapProvider implements SitemapProvider
{
    public function urls(): iterable
    {
        try {
            $projects = Project::query()
                ->where('status', 'published')
                ->orderBy('sort_order')
                ->get(['slug', 'updated_at']);
        } catch (Throwable) {
            // База данных может быть недоступна (например, при сборке конфигурации).
            return [];
        }

        $urls = [];

        foreach ($projects as $project) {
            if (!$project->slug) {
                continue;
            }

            $urls[] = [
                'loc' => '/portfolio/' . $project->slug,
                'lastmod' => optional($project->updated_at)->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        return $urls;
    }
}
