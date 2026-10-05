<?php

namespace App\Console\Commands;

use App\Seo\SitemapProvider;
use Illuminate\Console\Command;
use Throwable;

class GenerateSeoFiles extends Command
{
    protected $signature = 'seo:generate-files';

    protected $description = 'Generate sitemap.xml, robots.txt, ai.txt and llms.txt from config/seo.php';

    private const AI_USER_AGENTS = [
        'GPTBot',
        'OAI-SearchBot',
        'ClaudeBot',
        'anthropic-ai',
        'Google-Extended',
        'PerplexityBot',
        'Applebot-Extended',
        'CCBot',
        'meta-externalagent',
        'Bingbot',
    ];

    private const RESTRICTED_PATHS = [
        '/admin',
        '/login',
        '/logout',
        '/register',
        '/password',
        '/api/',
        '/profile',
        '/account',
        '/cart',
        '/checkout',
        '/search',
    ];

    public function handle(): int
    {
        $base = rtrim((string) config('seo.url'), '/');

        if ($base === '') {
            $this->error('config("seo.url") is empty. Set SEO_URL or APP_URL.');
            return self::FAILURE;
        }

        $urls = $this->collectUrls($base);

        $this->writeSitemap($urls);
        $this->writeRobots($base);
        $this->writeAiTxt($base);
        $this->writeLlmsTxt($base);

        $this->info(sprintf('SEO files generated in public/ (%d sitemap URLs).', count($urls)));

        return self::SUCCESS;
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function collectUrls(string $base): array
    {
        $urls = [];

        $static = config('seo.sitemap.urls', []);

        foreach ($static as $entry) {
            if (is_string($entry)) {
                $entry = ['loc' => $entry];
            }

            if (!is_array($entry) || empty($entry['loc'])) {
                continue;
            }

            $loc = $this->absoluteUrl($base, $entry['loc']);
            $urls[$loc] = array_filter([
                'loc' => $loc,
                'lastmod' => $entry['lastmod'] ?? null,
                'changefreq' => $entry['changefreq'] ?? null,
                'priority' => $entry['priority'] ?? null,
            ], fn ($value) => $value !== null && $value !== '');
        }

        foreach (config('seo.sitemap.providers', []) as $provider) {
            $instance = $this->resolveProvider($provider);

            if (!$instance) {
                continue;
            }

            try {
                $items = $instance->urls();
            } catch (Throwable $e) {
                $this->warn("Sitemap provider failed: {$e->getMessage()}");
                continue;
            }

            foreach ($items as $item) {
                if (is_string($item)) {
                    $item = ['loc' => $item];
                }

                if (!is_array($item) || empty($item['loc'])) {
                    continue;
                }

                $loc = $this->absoluteUrl($base, $item['loc']);

                if (isset($urls[$loc])) {
                    continue;
                }

                $urls[$loc] = array_filter([
                    'loc' => $loc,
                    'lastmod' => $item['lastmod'] ?? null,
                    'changefreq' => $item['changefreq'] ?? null,
                    'priority' => $item['priority'] ?? null,
                ], fn ($value) => $value !== null && $value !== '');
            }
        }

        return $urls;
    }

    private function resolveProvider(mixed $provider): ?SitemapProvider
    {
        if ($provider instanceof SitemapProvider) {
            return $provider;
        }

        if (is_string($provider) && class_exists($provider)) {
            $instance = app($provider);

            return $instance instanceof SitemapProvider ? $instance : null;
        }

        return null;
    }

    private function absoluteUrl(string $base, string $loc): string
    {
        if (preg_match('#^https?://#i', $loc)) {
            return $loc;
        }

        $loc = '/' . ltrim($loc, '/');

        if ($loc !== '/') {
            $loc = rtrim($loc, '/');
        }

        return $base . $loc;
    }

    /**
     * @param array<string, array<string, string>> $urls
     */
    private function writeSitemap(array $urls): void
    {
        $lines = [];
        $lines[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $lines[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';

        foreach ($urls as $entry) {
            $lines[] = '  <url>';
            $lines[] = '    <loc>' . $this->xml($entry['loc']) . '</loc>';

            if (!empty($entry['lastmod'])) {
                $lines[] = '    <lastmod>' . $this->xml($entry['lastmod']) . '</lastmod>';
            }

            if (!empty($entry['changefreq'])) {
                $lines[] = '    <changefreq>' . $this->xml($entry['changefreq']) . '</changefreq>';
            }

            if (!empty($entry['priority'])) {
                $lines[] = '    <priority>' . $this->xml($entry['priority']) . '</priority>';
            }

            $lines[] = '  </url>';
        }

        $lines[] = '</urlset>';

        $this->writeFile(public_path('sitemap.xml'), implode("\n", $lines) . "\n");
    }

    private function writeRobots(string $base): void
    {
        $aiAllowed = (bool) config('seo.ai.allowed', true);
        $aiDirective = $aiAllowed ? 'Allow: /' : 'Disallow: /';

        $lines = [];
        $lines[] = 'User-agent: *';
        $lines[] = 'Allow: /';
        $lines[] = '';

        foreach (self::RESTRICTED_PATHS as $path) {
            $lines[] = 'Disallow: ' . $path;
        }

        $lines[] = 'Disallow: /*utm_';
        $lines[] = 'Disallow: /*gclid=';
        $lines[] = 'Disallow: /*yclid=';
        $lines[] = 'Disallow: /*fbclid=';
        $lines[] = 'Disallow: /*?*sort=';
        $lines[] = 'Disallow: /*?*filter=';
        $lines[] = '';

        $lines[] = 'Allow: /favicon.ico';
        $lines[] = 'Allow: /favicons/';
        $lines[] = 'Allow: /site.webmanifest';
        $lines[] = 'Allow: /build/';
        $lines[] = 'Allow: /css/';
        $lines[] = 'Allow: /js/';
        $lines[] = 'Allow: /fonts/';
        $lines[] = 'Allow: /img/';
        $lines[] = 'Allow: /images/';
        $lines[] = 'Allow: /storage/';
        $lines[] = '';

        foreach (self::AI_USER_AGENTS as $agent) {
            $lines[] = 'User-agent: ' . $agent;
            $lines[] = $aiDirective;
            $lines[] = '';
        }

        $lines[] = 'Sitemap: ' . $base . '/sitemap.xml';

        $this->writeFile(public_path('robots.txt'), implode("\n", $lines) . "\n");
    }

    private function writeAiTxt(string $base): void
    {
        $aiAllowed = (bool) config('seo.ai.allowed', true);
        $trainingAllowed = (bool) config('seo.ai.training_allowed', false);

        $contact = (string) config('seo.ai.contact', '');
        $policy = (string) config('seo.ai.policy_url', '/ai.txt');
        $policyUrl = preg_match('#^https?://#i', $policy) ? $policy : $base . '/' . ltrim($policy, '/');

        $lines = [];
        $lines[] = '# AI Policy';
        $lines[] = '';
        $lines[] = 'name: ' . config('seo.site_name');
        $lines[] = 'url: ' . $base;
        $lines[] = 'description: ' . trim((string) config('seo.description'));
        if ($contact !== '') {
            $lines[] = 'contact: ' . $contact;
        }
        $lines[] = 'robots: ' . $base . '/robots.txt';
        $lines[] = 'llms: ' . $base . '/llms.txt';
        $lines[] = 'sitemap: ' . $base . '/sitemap.xml';
        $lines[] = 'policy: ' . $policyUrl;
        $lines[] = '';
        $lines[] = 'ai_crawling_allowed: ' . ($aiAllowed ? 'true' : 'false');
        $lines[] = 'ai_answer_generation_allowed: ' . ($aiAllowed ? 'true' : 'false');
        $lines[] = 'ai_training_allowed: ' . ($trainingAllowed ? 'true' : 'false');
        $lines[] = '';
        $lines[] = 'preferred_attribution: При использовании материалов указывайте название сайта и активную ссылку на первоисточник.';
        $lines[] = '';
        $lines[] = 'restricted_paths:';

        foreach (self::RESTRICTED_PATHS as $path) {
            $lines[] = '  - ' . $path;
        }

        $lines[] = '';
        $lines[] = 'notes:';
        $lines[] = '  - Публичный контент разрешено индексировать и использовать для формирования ответов.';
        $lines[] = $trainingAllowed
            ? '  - Использование контента для обучения моделей разрешено.'
            : '  - Использование контента для обучения моделей без отдельного согласия не разрешено.';

        $content = implode("\n", $lines) . "\n";

        $this->writeFile(public_path('ai.txt'), $content);
        $this->writeFile(public_path('.well-known/ai.txt'), $content);
    }

    private function writeLlmsTxt(string $base): void
    {
        $lines = [];
        $lines[] = '# ' . config('seo.site_name');
        $lines[] = '';
        $lines[] = '> ' . trim((string) config('seo.llms.summary', config('seo.description')));
        $lines[] = '';
        $lines[] = '## Official links';
        $lines[] = '';

        foreach (config('seo.llms.links', []) as $label => $path) {
            $lines[] = '- ' . $label . ': ' . $this->absoluteUrl($base, $path);
        }

        $lines[] = '';
        $lines[] = '## Main sections';
        $lines[] = '';

        foreach (config('seo.llms.sections', []) as $section) {
            if (empty($section['name']) || empty($section['path'])) {
                continue;
            }

            $description = !empty($section['description']) ? ' — ' . $section['description'] : '';
            $lines[] = '- ' . $section['name'] . ': ' . $this->absoluteUrl($base, $section['path']) . $description;
        }

        $lines[] = '';
        $lines[] = '## Facts';
        $lines[] = '';

        $lines[] = '- Официальное название: ' . config('seo.organization.name', config('seo.site_name')) . '.';

        foreach (config('seo.llms.facts', []) as $fact) {
            $lines[] = '- ' . $fact;
        }

        $lines[] = '';
        $lines[] = '## AI usage policy';
        $lines[] = '';

        $aiAllowed = (bool) config('seo.ai.allowed', true);
        $trainingAllowed = (bool) config('seo.ai.training_allowed', false);
        $contact = (string) config('seo.ai.contact', '');

        $lines[] = $aiAllowed
            ? '- Public pages may be crawled and summarized.'
            : '- Public pages must not be crawled or summarized.';
        $lines[] = '- Answers may cite this site with canonical URLs.';
        $lines[] = $trainingAllowed
            ? '- Content may be used for model training.'
            : '- Do not use content for model training without separate permission.';
        if ($contact !== '') {
            $lines[] = '- For removal or copyright requests contact: ' . $contact . '.';
        }

        $lines[] = '';
        $lines[] = '## Citation preference';
        $lines[] = '';
        $lines[] = 'При цитировании используйте канонический URL страницы и сохраняйте оригинальный заголовок.';

        $this->writeFile(public_path('llms.txt'), implode("\n", $lines) . "\n");
    }

    private function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private function writeFile(string $path, string $content): void
    {
        $directory = dirname($path);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($path, $content);
    }
}
