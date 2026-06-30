<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name'   => 'Sophie Lotanna',
                'role'   => 'Event Manager',
                'rating' => 5,
                'text'   => 'The hotel is well located. Rooms are very clean and the service is top tier. The breakfast was absolutely delicious and the staff were incredibly warm and welcoming.',
                'avatar' => 'https://images.pexels.com/photos/7820318/pexels-photo-7820318.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&fit=crop',
            ],
            [
                'name'   => 'Anidebe Samuel',
                'role'   => 'Tourist',
                'rating' => 5,
                'text'   => 'Brilliant staff and exceptional customer service. The facilities are fantastic — the pool area is stunning at night. Buffet breakfast daily is very generous and varied.',
                'avatar' => 'https://images.pexels.com/photos/29649747/pexels-photo-29649747.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&fit=crop',
            ],
            [
                'name'   => 'Harrison & Family',
                'role'   => 'City Visitors',
                'rating' => 5,
                'text'   => 'The restaurants were all very good and the friendly, helpful staff made our overall experience wonderful. We will definitely be returning next year for our anniversary.',
                'avatar' => 'https://images.pexels.com/photos/7820311/pexels-photo-7820311.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&fit=crop',
            ],
            [
                'name'   => 'Gbenga Ayodele',
                'role'   => 'Business Traveller',
                'rating' => 5,
                'text'   => 'All rooms are renovated and modern. The staff are very willing to help you plan your day. The penthouse view at sunset is something I will never forget. Highly recommend!',
                'avatar' => 'https://images.pexels.com/photos/6474532/pexels-photo-6474532.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&fit=crop',
            ],
            [
                'name'   => 'Chioma Obi',
                'role'   => 'Wedding Guest',
                'rating' => 5,
                'text'   => 'We hosted our reception here and it was absolutely magical. The event hall was stunning, the food was incredible, and the team was so professional throughout.',
                'avatar' => 'https://images.pexels.com/photos/7820318/pexels-photo-7820318.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&fit=crop',
            ],
            [
                'name'   => 'Mr. Adeyemi',
                'role'   => 'Corporate Guest',
                'rating' => 5,
                'text'   => 'I stay here on every business trip to Asaba. The conference facilities are world-class, the WiFi is fast, and the service is consistently excellent. My go-to hotel.',
                'avatar' => 'https://images.pexels.com/photos/6474532/pexels-photo-6474532.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&fit=crop',
            ],
            [
                'name'   => 'Ngozi Okeke',
                'role'   => 'Apartment Guest',
                'rating' => 5,
                'text'   => 'I booked the serviced apartment for a three-week work stay in Asaba and it felt like home — full kitchen, daily housekeeping, and hotel support whenever I needed it. Perfect for long stays.',
                'avatar' => 'https://images.pexels.com/photos/7820318/pexels-photo-7820318.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&fit=crop',
            ],
            [
                'name'   => 'The Eze Family',
                'role'   => 'Family Apartment Guest',
                'rating' => 5,
                'text'   => 'The two-bedroom family apartment gave us space, privacy, and a kitchen, while still enjoying the pool and restaurant. Ideal for relocating to Asaba with kids.',
                'avatar' => 'https://images.pexels.com/photos/7820311/pexels-photo-7820311.jpeg?auto=compress&cs=tinysrgb&w=200&h=200&fit=crop',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
