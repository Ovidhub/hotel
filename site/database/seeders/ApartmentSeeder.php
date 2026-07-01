<?php

namespace Database\Seeders;

use App\Models\Apartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ApartmentSeeder extends Seeder
{
    public function run(): void
    {
        $sharedAmenities = [
            'Fully-equipped kitchen', 'Air conditioning', 'Smart TV', 'High-speed WiFi',
            'Daily housekeeping', '24/7 power supply', 'Security & CCTV', 'Parking',
        ];

        $apartments = [
            [
                'slug'        => 'classic-room',
                'name'        => 'Classic Room',
                'type'        => 'Serviced Apartment',
                'price'       => 100000,
                'status'      => 'Available',
                'bedrooms'    => 1,
                'bathrooms'   => 1,
                'occupancy'   => 2,
                'description' => 'A comfortable, fully-serviced classic room apartment in Asaba — ideal for solo travelers and short business stays, with a private bathroom, kitchenette access, daily housekeeping, and round-the-clock power and security.',
                'amenities'   => $sharedAmenities,
                'sort'        => 1,
            ],
            [
                'slug'        => 'deluxe-classic-apartment',
                'name'        => 'Deluxe Classic Apartment',
                'type'        => 'Serviced Apartment',
                'price'       => 130000,
                'status'      => 'Available',
                'bedrooms'    => 1,
                'bathrooms'   => 1,
                'occupancy'   => 2,
                'description' => 'An upgraded deluxe serviced apartment in Asaba with more space and premium finishes — a relaxing base for couples and business travelers, complete with kitchen, smart TV, fast WiFi, and hotel-grade support.',
                'amenities'   => $sharedAmenities,
                'sort'        => 2,
            ],
            [
                'slug'        => 'supreme-2-bedroom-apartment',
                'name'        => 'Supreme 2-Bedroom Apartment',
                'type'        => 'Family Apartment',
                'price'       => 350000,
                'status'      => 'Available',
                'bedrooms'    => 2,
                'bathrooms'   => 2,
                'occupancy'   => 4,
                'description' => 'A spacious two-bedroom serviced apartment in Asaba with a full living and dining area — perfect for families and small teams who want privacy and space, backed by Hotel Benizia housekeeping, power, and security.',
                'amenities'   => $sharedAmenities,
                'sort'        => 3,
            ],
            [
                'slug'        => 'supreme-4-bedroom-apartment',
                'name'        => 'Supreme 4-Bedroom Apartment',
                'type'        => 'Luxury Apartment',
                'price'       => 650000,
                'status'      => 'Available',
                'bedrooms'    => 4,
                'bathrooms'   => 4,
                'occupancy'   => 8,
                'description' => 'Our largest and most luxurious serviced apartment in Asaba — four en-suite bedrooms with generous living space, ideal for large families, groups, and executive stays, with full kitchen, daily housekeeping, 24/7 power, and security.',
                'amenities'   => $sharedAmenities,
                'sort'        => 4,
            ],
        ];

        foreach ($apartments as $data) {
            $gallery = $this->galleryFor($data['slug']);

            Apartment::create(array_merge($data, [
                'price_label' => 'NGN ' . number_format($data['price']),
                'image'       => $gallery[0] ?? 'https://hotelbenizia.ng/wp-content/uploads/2025/05/balcony-homepage-pics-600x798.jpg',
                'gallery'     => $gallery,
                'is_active'   => true,
            ]));
        }
    }

    /**
     * Build a gallery of root-relative URLs from the imported WebP photos in
     * public/img/apartments/<slug> (created by `apartments:import-photos`).
     *
     * @return array<int, string>
     */
    protected function galleryFor(string $slug): array
    {
        $dir = public_path('img/apartments/' . $slug);

        if (! File::isDirectory($dir)) {
            return [];
        }

        return collect(File::files($dir))
            ->filter(fn ($f) => strtolower($f->getExtension()) === 'webp')
            ->sortBy(fn ($f) => (int) pathinfo($f->getFilename(), PATHINFO_FILENAME))
            ->map(fn ($f) => '/img/apartments/' . $slug . '/' . $f->getFilename())
            ->values()
            ->all();
    }
}
