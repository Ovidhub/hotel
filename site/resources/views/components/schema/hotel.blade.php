@php
    $sameAs = collect(config('hotel.socials', []))
        ->filter(fn ($url) => $url && $url !== '#')
        ->values()
        ->all();

    $base  = rtrim(config('hotel.canonical', 'https://hotelbenizia.ng'), '/');
    $theme = config('hotel.theme', 'default');

    // Images: the default (Benizia) theme keeps its property shots; a named
    // theme advertises its own local images. Built from the canonical host.
    $images = $theme === 'default'
        ? [
            $base . '/img/property/hotel-exterior.webp',
            $base . '/img/property/hotel-pool.webp',
            $base . '/img/property/hotel-compound.webp',
            $base . '/img/property/hotel-entrance.webp',
        ]
        : [
            $base . '/img/themes/' . $theme . '/hero-bg.webp',
            $base . '/img/themes/' . $theme . '/why-bg.webp',
            $base . '/img/themes/' . $theme . '/amenities-bg.webp',
        ];

    $data = [
        '@context' => 'https://schema.org',
        '@type'    => ['Hotel', 'LodgingBusiness'],
        'name'     => config('hotel.name', 'Hotel Benizia'),
        'url'      => $base,
        'telephone' => config('hotel.phone_href', '+2348134062487'),
        'email'    => config('hotel.email', 'hotelbenizia66@gmail.com'),
        'description' => config('hotel.description'),
        'image' => $images,
        'address' => [
            '@type'          => 'PostalAddress',
            'streetAddress'  => config('hotel.street_address', '1 Kingsley Emu Street'),
            'addressLocality' => config('hotel.address_locality', 'Asaba'),
            'addressRegion'  => config('hotel.address_region', 'Delta State'),
            'addressCountry' => config('hotel.address_country', 'NG'),
            'postalCode'     => config('hotel.postal_code', '320242'),
        ],
        'geo' => [
            '@type'     => 'GeoCoordinates',
            'latitude'  => config('hotel.geo.lat', 6.1980),
            'longitude' => config('hotel.geo.lng', 6.7290),
        ],
        'hasMap'     => config('hotel.maps_url', 'https://www.google.com/maps?q=Hotel+Benizia+Asaba'),
        'areaServed' => [
            ['@type' => 'City',  'name' => 'Asaba'],
            ['@type' => 'State', 'name' => 'Delta State'],
        ],
        'priceRange' => 'NGN 30,000 - NGN 180,000',
        'currenciesAccepted' => 'NGN',
        'amenityFeature' => [
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Breakfast Included'],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Swimming Pool'],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'High Speed Wifi'],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Spa & Wellness'],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Event Halls'],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Serviced Apartments'],
        ],
        // NOTE: aggregateRating intentionally omitted until real guest reviews
        // are collected — Google penalises self-serving/placeholder review data.
    ];

    if (! empty($sameAs)) {
        $data['sameAs'] = $sameAs;
    }
@endphp
<script type="application/ld+json">{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
