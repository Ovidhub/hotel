<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $includes = ['Complimentary Breakfast', 'Swimming Pool Access', 'Gym Access', 'Free WiFi', 'Airport Pickup (on request)'];
        $policies = ['Check-in: 2:00 PM', 'Check-out: 12:00 PM', 'No smoking', 'Free cancellation 24h before check-in'];

        $rooms = [
            [
                'slug'        => 'deluxe-classic',
                'name'        => 'Deluxe Classic',
                'category'    => 'Comfort Room',
                'price'       => 35000,
                'size'        => '300 sq ft',
                'sqm'         => 28,
                'guests'      => 2,
                'beds'        => 1,
                'baths'       => 1,
                'excerpt'     => 'A warm, well-furnished room for simple comfort — breakfast, pool access, and a calm Asaba stay.',
                'description' => 'The Deluxe Classic is designed for guests who want a refined but practical stay in Asaba. It offers comfortable bedding, complimentary breakfast, pool and gym access, reliable 24-hour power, and attentive room service.',
                'amenities'   => ['Breakfast included', 'Air conditioning', 'Smart TV', 'High speed WiFi', 'Pool access', 'Gym access', 'Room service'],
                'best_for'    => ['Solo stays', 'Short business trips', 'Weekend visits'],
                'sort'        => 1,
            ],
            [
                'slug'        => 'alcove-room',
                'name'        => 'Alcove Room',
                'category'    => 'Signature Room',
                'price'       => 50000,
                'size'        => '400 sq ft',
                'sqm'         => 37,
                'guests'      => 2,
                'beds'        => 1,
                'baths'       => 1,
                'excerpt'     => 'A stylish room with a private alcove sitting area for guests who want a little more space and calm.',
                'description' => 'The Alcove Room offers a distinctive layout with a cosy alcove sitting area, ideal for couples and travelers who value a more private, relaxed atmosphere — with all core Hotel Benizia guest benefits included.',
                'amenities'   => ['Breakfast included', 'Private sitting area', 'Air conditioning', 'Smart TV', 'High speed WiFi', 'Pool access', 'Gym access'],
                'best_for'    => ['Couples', 'Relaxation stays', 'Quiet work sessions'],
                'sort'        => 2,
            ],
            [
                'slug'        => 'diplomatic-suite',
                'name'        => 'Diplomatic Suite',
                'category'    => 'Executive Suite',
                'price'       => 85000,
                'size'        => '520 sq ft',
                'sqm'         => 48,
                'guests'      => 3,
                'beds'        => 1,
                'baths'       => 1,
                'excerpt'     => 'A spacious executive suite with a lounge area, work comfort, and upgraded hospitality benefits.',
                'description' => 'The Diplomatic Suite is ideal for executives and VIP guests who need room to relax or work. It combines suite-level comfort and a separate lounge feel with full access to Hotel Benizia dining, bar, pool, and priority guest service.',
                'amenities'   => ['Breakfast included', 'Suite lounge', 'Executive work desk', 'Air conditioning', 'Smart TV', 'High speed WiFi', 'Pool access', 'Gym access', 'Priority service'],
                'best_for'    => ['Executives', 'VIP stays', 'Business meetings'],
                'sort'        => 3,
            ],
            [
                'slug'        => 'presidential-suite',
                'name'        => 'Presidential Suite',
                'category'    => 'Presidential',
                'price'       => 185000,
                'size'        => '900 sq ft',
                'sqm'         => 84,
                'guests'      => 4,
                'beds'        => 2,
                'baths'       => 2,
                'excerpt'     => 'The most exclusive stay at Hotel Benizia — a grand suite with generous living space and premium service.',
                'description' => 'The Presidential Suite is Hotel Benizia at its finest: expansive living and sleeping areas, premium finishes, and the highest level of personal service. Built for dignitaries, executives, and special occasions in Asaba, with full access to every hotel facility.',
                'amenities'   => ['Breakfast included', 'Separate living room', 'Dining area', 'Butler service (on request)', 'Air conditioning', '65" Smart TV', 'Wet bar', 'High speed WiFi', 'Pool access', 'Gym & Spa access'],
                'best_for'    => ['Dignitaries & VIPs', 'Executive stays', 'Special occasions'],
                'sort'        => 4,
            ],
        ];

        foreach ($rooms as $data) {
            $gallery = $this->galleryFor($data['slug']);

            Room::create(array_merge($data, [
                'price_label' => 'NGN ' . number_format($data['price']),
                'image'       => $gallery[0] ?? 'https://hotelbenizia.ng/img/rooms/deluxe-classic/1.webp',
                'gallery'     => $gallery,
                'rating'      => null,
                'reviews'     => 0,
                'includes'    => $includes,
                'policies'    => $policies,
                'is_active'   => true,
                'units'       => 1,
            ]));
        }
    }

    /**
     * Build a gallery of root-relative URLs from the imported WebP photos in
     * public/img/rooms/<slug> (created by `rooms:import-photos`).
     *
     * @return array<int, string>
     */
    protected function galleryFor(string $slug): array
    {
        $dir = public_path('img/rooms/' . $slug);

        if (! File::isDirectory($dir)) {
            return [];
        }

        return collect(File::files($dir))
            ->filter(fn ($f) => strtolower($f->getExtension()) === 'webp')
            ->sortBy(fn ($f) => (int) pathinfo($f->getFilename(), PATHINFO_FILENAME))
            ->map(fn ($f) => '/img/rooms/' . $slug . '/' . $f->getFilename())
            ->values()
            ->all();
    }
}
