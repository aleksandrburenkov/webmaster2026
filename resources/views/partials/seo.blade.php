@php
    $sectionTitle = trim((string) $__env->yieldContent('title'));
    $sectionDescription = trim((string) $__env->yieldContent('meta_description'));

    $seo = array_merge([
        'title' => $sectionTitle !== '' ? $sectionTitle : config('seo.default_title'),
        'description' => $sectionDescription !== '' ? $sectionDescription : config('seo.description'),
        'canonical' => request()->url(),
        'robots' => null,
        'type' => 'website',
        'image' => config('seo.image'),
        'schema' => [],
    ], $seo ?? []);

    $seoTitle = trim((string) $seo['title']);

    if (config('seo.title_suffix') && !str_contains($seoTitle, config('seo.title_suffix'))) {
        $seoTitle = trim($seoTitle . ' | ' . config('seo.title_suffix'));
    }

    $seoDescription = trim((string) $seo['description']);

    $seoImage = null;
    if (!empty($seo['image'])) {
        $seoImage = preg_match('#^https?://#i', $seo['image'])
            ? $seo['image']
            : asset($seo['image']);
    }

    $robotsDirectives = [];

    if ($seo['robots']) {
        $robotsDirectives[] = $seo['robots'];
    } elseif (config('seo.robots.index') === false || config('seo.robots.follow') === false) {
        $robotsDirectives[] = config('seo.robots.index') ? 'index' : 'noindex';
        $robotsDirectives[] = config('seo.robots.follow') ? 'follow' : 'nofollow';
    }

    $schemaGraph = [];

    $schemaGraph[] = [
        '@type' => 'WebSite',
        'name' => config('seo.site_name'),
        'url' => config('seo.url'),
        'inLanguage' => str_replace('_', '-', config('app.locale', 'ru')),
    ];

    if (config('seo.organization.name')) {
        $organization = [
            '@type' => config('seo.geo.enabled') ? 'LocalBusiness' : 'Organization',
            'name' => config('seo.organization.name'),
            'url' => config('seo.url'),
        ];

        if (config('seo.organization.legal_name')) {
            $organization['legalName'] = config('seo.organization.legal_name');
        }

        if (config('seo.organization.logo')) {
            $organization['logo'] = asset(config('seo.organization.logo'));
        }

        if (config('seo.organization.phone')) {
            $organization['telephone'] = config('seo.organization.phone');
        }

        if (config('seo.organization.email')) {
            $organization['email'] = config('seo.organization.email');
        }

        if (config('seo.organization.social')) {
            $organization['sameAs'] = array_values(config('seo.organization.social'));
        }

        $address = array_filter([
            'streetAddress' => config('seo.organization.address.street'),
            'addressLocality' => config('seo.organization.address.locality'),
            'addressRegion' => config('seo.organization.address.region'),
            'postalCode' => config('seo.organization.address.postal_code'),
            'addressCountry' => config('seo.organization.address.country'),
        ]);

        if ($address) {
            $organization['address'] = array_merge(['@type' => 'PostalAddress'], $address);
        }

        if (config('seo.geo.enabled') && config('seo.geo.position')) {
            $position = str_replace(';', ',', config('seo.geo.position'));
            $parts = array_map('trim', explode(',', $position));

            if (count($parts) >= 2 && $parts[0] !== '' && $parts[1] !== '') {
                $organization['geo'] = [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $parts[0],
                    'longitude' => $parts[1],
                ];
            }
        }

        $schemaGraph[] = $organization;
    }

    foreach (($seo['schema'] ?? []) as $extraSchema) {
        $schemaGraph[] = $extraSchema;
    }

    $schemaGraph = array_values(array_filter($schemaGraph));

    $schemaPayload = [
        '@context' => 'https://schema.org',
        '@graph' => $schemaGraph,
    ];
@endphp

@if(config('seo.enabled'))
    <title>{{ $seoTitle }}</title>

    @if($seoDescription)
        <meta name="description" content="{{ $seoDescription }}">
    @endif

    @if(!empty($robotsDirectives))
        <meta name="robots" content="{{ implode(',', $robotsDirectives) }}">
    @endif

    <link rel="canonical" href="{{ $seo['canonical'] }}">

    {{-- Open Graph --}}
    <meta property="og:site_name" content="{{ config('seo.site_name') }}">
    <meta property="og:type" content="{{ $seo['type'] }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    <meta property="og:locale" content="{{ config('seo.locale') }}">

    @if($seoImage)
        <meta property="og:image" content="{{ $seoImage }}">
        <meta property="og:image:alt" content="{{ $seoTitle }}">
    @endif

    {{-- Twitter --}}
    <meta name="twitter:card" content="{{ $seoImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    @if($seoImage)
        <meta name="twitter:image" content="{{ $seoImage }}">
    @endif

    {{-- GEO meta only for real geographic projects --}}
    @if(config('seo.geo.enabled'))
        @if(config('seo.geo.region'))
            <meta name="geo.region" content="{{ config('seo.geo.region') }}">
        @endif

        @if(config('seo.geo.placename'))
            <meta name="geo.placename" content="{{ config('seo.geo.placename') }}">
        @endif

        @if(config('seo.geo.position'))
            <meta name="geo.position" content="{{ config('seo.geo.position') }}">
            <meta name="ICBM" content="{{ str_replace(';', ',', config('seo.geo.position')) }}">
        @endif
    @endif

    {{-- Multilingual hreflang --}}
    @foreach(config('seo.locales', []) as $locale => $path)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ url($path) }}">
    @endforeach

    @if(config('seo.locales'))
        <link rel="alternate" hreflang="x-default" href="{{ url('/') }}">
    @endif

    {{-- Favicons and manifest --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicons/favicon-48x48.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-touch-icon.png') }}">

    @if(file_exists(public_path('favicons/favicon.svg')))
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicons/favicon.svg') }}">
    @endif

    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="{{ config('seo.theme_color') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ config('seo.site_name') }}">

    {{-- JSON-LD --}}
    @if(count($schemaGraph))
        <script type="application/ld+json">{!! json_encode($schemaPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @endif
@endif
