<?php
return [
  'name' => env('HOTEL_NAME', 'Hotel Benizia'),
  'tagline' => env('HOTEL_TAGLINE', 'Luxury in the heart of Asaba'),
  'phone' => env('HOTEL_PHONE', '+234 813 406 2487'),
  'phone_href' => env('HOTEL_PHONE_HREF', '+2348134062487'),
  'email' => env('HOTEL_EMAIL', 'hotelbenizia66@gmail.com'),
  'address' => env('HOTEL_ADDRESS', '1 Kingsley Emu Street, Central Area, Asaba 320242, Delta State'),
  'theme' => env('THEME', 'default'),
  // Check-in / check-out times (site-wide policy).
  'check_in' => '2:00 PM',
  'check_out' => '12:00 noon',
  // HB Apartments has its own booking contact, separate from the hotel.
  'apartments' => [
    'phone' => '+234 803 612 5379',
    'phone_href' => '+2348036125379',
    'email' => 'hbapartment1@gmail.com',
  ],
  'canonical' => env('HOTEL_CANONICAL', 'https://hotelbenizia.ng'),
  // One-line description used in local-SEO / structured data.
  'description' => env('HOTEL_DESCRIPTION', 'Hotel Benizia is a luxury hotel and serviced apartments in Asaba, Delta State, offering premium rooms, short-let apartments, a 24-hour restaurant and bar, swimming pool, spa, and event halls.'),
  // Structured postal address parts (schema.org PostalAddress).
  'street_address'   => env('HOTEL_STREET', '1 Kingsley Emu Street'),
  'address_locality' => env('HOTEL_CITY', 'Asaba'),
  'address_region'   => env('HOTEL_REGION', 'Delta State'),
  'address_country'  => env('HOTEL_COUNTRY', 'NG'),
  'postal_code'      => env('HOTEL_POSTAL', '320242'),
  // Approximate coordinates (used in local-SEO schema). Env-driven per hotel.
  'geo' => ['lat' => (float) env('HOTEL_GEO_LAT', 6.1980), 'lng' => (float) env('HOTEL_GEO_LNG', 6.7290)],
  'maps_url' => env('HOTEL_MAPS_URL', 'https://www.google.com/maps?q=Hotel+Benizia+Asaba'),
  'socials' => [
    'facebook' => '#','twitter' => '#','instagram' => '#','youtube' => '#','pinterest' => '#',
  ],
  'nav' => [
    ['label'=>'Home','route'=>'home'],
    ['label'=>'Rooms','route'=>'rooms.index'],
    ['label'=>'HB Apartments','route'=>'apartments.index'],
    ['label'=>'Restaurant','route'=>'restaurant'],
    ['label'=>'Gallery','route'=>'gallery'],
    ['label'=>'Blog','route'=>'blog.index'],
    ['label'=>'About','route'=>'about'],
    ['label'=>'Contact','route'=>'contact'],
  ],
  'ticker' => ['Breakfast Included','Swimming Pool','High Speed WiFi','Spa & Wellness','Pick Up & Drop','Fitness Hub','24/7 Restaurant','Live Entertainment','Event Halls','Free Parking','Room Service','24/7 Security'],
  'booking' => ['commitment_percent'=>40,'cancellation_hours'=>24,'balance_note'=>'Pay the remaining balance at check-in after reservation confirmation.'],
];
