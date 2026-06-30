<?php
return [
  'name' => 'Hotel Benizia',
  'tagline' => 'Luxury in the heart of Asaba',
  'phone' => '+234 813 406 2487',
  'phone_href' => '+2348134062487',
  'email' => 'booking@hotelbenizia.ng',
  'address' => '1 Kingsley Emu Street, Central Area, Asaba 320242, Delta State',
  'canonical' => 'https://hotelbenizia.ng',
  // Approximate coordinates for Central Area, Asaba, Delta State (used in local-SEO schema).
  'geo' => ['lat' => 6.1980, 'lng' => 6.7290],
  'maps_url' => 'https://www.google.com/maps?q=Hotel+Benizia+Asaba',
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
