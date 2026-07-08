@php
    $sameAs = collect(config('hotel.socials', []))
        ->filter(fn ($url) => $url && $url !== '#')
        ->values()
        ->all();

    $data = [
        '@context' => 'https://schema.org',
        '@type'    => ['Hotel', 'LodgingBusiness'],
        'name'     => config('hotel.name', 'Hotel Benizia'),
        'url'      => config('hotel.canonical', 'https://hotelbenizia.ng'),
        'telephone' => config('hotel.phone_href', '+2348134062487'),
        'email'    => config('hotel.email', 'hotelbenizia66@gmail.com'),
        'description' => 'Hotel Benizia is a luxury hotel and serviced apartments in Asaba, Delta State, offering premium rooms, short-let apartments, a 24-hour restaurant and bar, swimming pool, spa, and event halls.',
        'image' => [
            'https://hotelbenizia.ng/img/property/hotel-exterior.webp',
            'https://hotelbenizia.ng/img/property/hotel-pool.webp',
            'https://hotelbenizia.ng/img/property/hotel-compound.webp',
            'https://hotelbenizia.ng/img/property/hotel-entrance.webp',
        ],
        'address' => [
            '@type'          => 'PostalAddress',
            'streetAddress'  => '1 Kingsley Emu Street',
            'addressLocality' => 'Asaba',
            'addressRegion'  => 'Delta State',
            'addressCountry' => 'NG',
            'postalCode'     => '320242',
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
