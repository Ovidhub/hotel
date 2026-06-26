@php
    $data = [
        '@context' => 'https://schema.org',
        '@type'    => 'Hotel',
        'name'     => config('hotel.name', 'Hotel Benizia'),
        'url'      => config('hotel.canonical', 'https://hotelbenizia.ng'),
        'telephone' => config('hotel.phone_href', '+2348134062487'),
        'description' => 'Luxury hotel in Asaba, Delta State with premium rooms, restaurant, bar, swimming pool, event halls, and 24-hour hospitality services.',
        'image' => [
            'https://hotelbenizia.ng/wp-content/uploads/2025/05/front-page-banner.jpg',
            'https://hotelbenizia.ng/wp-content/uploads/2025/06/hotel-benizia-swimming-pool-and-bar-1200x959.jpg',
            'https://hotelbenizia.ng/wp-content/uploads/2025/06/hotel-benizi-entrance-370x554.jpg',
        ],
        'address' => [
            '@type'          => 'PostalAddress',
            'streetAddress'  => '1 Kingsley Emu Street',
            'addressLocality' => 'Asaba',
            'addressRegion'  => 'Delta State',
            'addressCountry' => 'NG',
        ],
        'priceRange' => 'NGN 30,000 - NGN 180,000',
        'amenityFeature' => [
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Breakfast Included'],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Swimming Pool'],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'High Speed Wifi'],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Spa & Wellness'],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Event Halls'],
        ],
        'aggregateRating' => [
            '@type'       => 'AggregateRating',
            'ratingValue' => 4.9,
            'reviewCount' => 120,
            'bestRating'  => 5,
            'worstRating' => 1,
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
