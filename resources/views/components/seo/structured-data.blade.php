@props([
    'type' => 'EducationalOrganization',
])

@php
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => $type,
        'name' => config('site.name'),
        'description' => config('site.description'),
        'url' => config('app.url'),
        'telephone' => config('site.phone'),
        'email' => config('site.email'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => config('site.address'),
            'addressLocality' => config('site.city', 'Huancayo'),
            'addressRegion' => config('site.region', 'Junín'),
            'addressCountry' => config('site.country', 'PE'),
        ],
        'sameAs' => array_values(array_filter(config('site.social', []))),
    ];
@endphp

<script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
