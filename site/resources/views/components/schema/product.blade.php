@props([
    'name',
    'description',
    'image',
    'price',
    'url',
    'rating' => null,
])

@php
    $data = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => $name,
        'description' => $description,
        'image'       => $image,
        'url'         => $url,
        'offers'      => [
            '@type'           => 'Offer',
            'priceCurrency'   => 'NGN',
            'price'           => (int) $price,
            'availability'    => 'https://schema.org/InStock',
            'url'             => $url,
        ],
    ];

    if ($rating !== null) {
        $data['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => $rating['ratingValue'],
            'reviewCount' => $rating['reviewCount'],
        ];
    }
@endphp
<script type="application/ld+json">{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
