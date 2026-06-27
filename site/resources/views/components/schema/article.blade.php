@props([
    'title',
    'description',
    'image',
    'author',
    'datePublished',
    'url',
    'authorType' => 'Organization',
])

@php
    $data = [
        '@context'      => 'https://schema.org',
        '@type'         => 'BlogPosting',
        'headline'      => $title,
        'description'   => $description,
        'image'         => $image,
        'datePublished' => $datePublished,
        'url'           => $url,
        'author'        => [
            '@type' => $authorType,
            'name'  => $author,
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name'  => config('hotel.name', 'Hotel Benizia'),
            'logo'  => [
                '@type' => 'ImageObject',
                'url'   => config('hotel.canonical', 'https://hotelbenizia.ng') . '/images/logo.png',
            ],
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id'   => $url,
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
