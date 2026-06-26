<?php

namespace Database\Seeders;

use App\Models\Apartment;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    public function run(): void
    {
        $apartments = [
            [
                'slug'        => 'one-bedroom-service-apartment',
                'name'        => 'One Bedroom Service Apartment',
                'type'        => 'Short-let Apartment',
                'price'       => 95000,
                'price_label' => 'NGN 95,000',
                'status'      => 'Available',
                'image'       => 'https://hotelbenizia.ng/wp-content/uploads/2025/05/balcony-homepage-pics-600x798.jpg',
                'gallery'     => null,
                'bedrooms'    => 1,
                'bathrooms'   => 1,
                'occupancy'   => 2,
                'description' => 'A private serviced apartment for extended stays, business travelers, and guests who prefer apartment-style living with hotel support.',
                'amenities'   => ['Kitchenette', 'Living area', 'Daily housekeeping', 'Wifi', 'Power supply', 'Security'],
                'is_active'   => true,
                'sort'        => 1,
            ],
            [
                'slug'        => 'two-bedroom-family-apartment',
                'name'        => 'Two Bedroom Family Apartment',
                'type'        => 'Family Apartment',
                'price'       => 150000,
                'price_label' => 'NGN 150,000',
                'status'      => 'Occupied',
                'image'       => 'https://hotelbenizia.ng/wp-content/uploads/2025/05/front-page-banner.jpg',
                'gallery'     => null,
                'bedrooms'    => 2,
                'bathrooms'   => 2,
                'occupancy'   => 4,
                'description' => 'A spacious apartment for families, small teams, or long-stay guests who want extra privacy and living space.',
                'amenities'   => ['Kitchenette', 'Dining area', 'Laundry support', 'Wifi', 'Pool access', 'Parking'],
                'is_active'   => true,
                'sort'        => 2,
            ],
            [
                'slug'        => 'executive-studio-apartment',
                'name'        => 'Executive Studio Apartment',
                'type'        => 'Executive Studio',
                'price'       => 70000,
                'price_label' => 'NGN 70,000',
                'status'      => 'Maintenance',
                'image'       => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/other-services-780x521.jpg',
                'gallery'     => null,
                'bedrooms'    => 1,
                'bathrooms'   => 1,
                'occupancy'   => 2,
                'description' => 'A compact executive apartment for guests who need independence, privacy, and hotel-grade service in Asaba.',
                'amenities'   => ['Work desk', 'Kitchenette', 'Smart TV', 'Wifi', 'Housekeeping', 'Security'],
                'is_active'   => true,
                'sort'        => 3,
            ],
        ];

        foreach ($apartments as $apartment) {
            Apartment::create($apartment);
        }
    }
}
