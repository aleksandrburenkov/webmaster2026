<?php

namespace App\Seo;

interface SitemapProvider
{
    /**
     * Вернуть список URL для sitemap.
     *
     * Каждый элемент может быть строкой пути/URL либо массивом:
     * ['loc' => '/path', 'lastmod' => 'YYYY-MM-DD', 'changefreq' => 'weekly', 'priority' => '0.8'].
     *
     * @return iterable<int, string|array<string, mixed>>
     */
    public function urls(): iterable;
}
