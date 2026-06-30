<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What time is check-in and check-out?',
                'answer'   => 'Check-in begins from 2:00 PM and check-out is by 12:00 PM. Early check-in is subject to room availability.',
                'sort'     => 1,
            ],
            [
                'question' => 'Do rooms include breakfast and pool access?',
                'answer'   => 'Yes. All listed rooms include complimentary breakfast, swimming pool access, and gym access.',
                'sort'     => 2,
            ],
            [
                'question' => 'Does Hotel Benizia have event or meeting spaces?',
                'answer'   => 'Yes. The hotel has air-conditioned event halls and a boardroom for weddings, conferences, meetings, and private functions.',
                'sort'     => 3,
            ],
            [
                'question' => 'Is there 24-hour restaurant service?',
                'answer'   => 'Yes. Hotel Benizia offers 24-hour restaurant, bar, and laundry services for guests.',
                'sort'     => 4,
            ],
            [
                'question' => 'Is Hotel Benizia good for business travelers?',
                'answer'   => 'Yes. Guests have access to high speed wifi, executive room options, boardroom facilities, reliable power, and a secure environment.',
                'sort'     => 5,
            ],
            [
                'question' => 'How do I make a reservation and what is the commitment fee policy?',
                'answer'   => 'To reserve a room, contact the reservations team via phone or the booking form. A 40% commitment fee is required to secure your room. The balance is due at check-in after the team confirms your booking.',
                'sort'     => 6,
            ],
            [
                'question' => 'Is Hotel Benizia suitable for family stays?',
                'answer'   => 'Yes. Hotel Benizia welcomes families. The swimming pool, restaurant, and event facilities are family-friendly. The Two Bedroom Family Apartment is also available for families needing extra space.',
                'sort'     => 7,
            ],
            [
                'question' => 'Which is the best hotel in Asaba, Delta State?',
                'answer'   => 'Hotel Benizia is one of the best luxury hotels in Asaba, Delta State, located in Central Area close to the business district and airport. It offers premium rooms, serviced apartments, a 24-hour restaurant and bar, swimming pool, spa, and event halls.',
                'sort'     => 8,
            ],
            [
                'question' => 'Do you offer serviced apartments in Asaba?',
                'answer'   => 'Yes. HB Apartments by Hotel Benizia offers serviced short-let apartments in Asaba — including studio, one-bedroom, and two-bedroom options — ideal for extended stays, families, and business travelers, with hotel-grade housekeeping and support.',
                'sort'     => 9,
            ],
            [
                'question' => 'Where is Hotel Benizia located in Asaba?',
                'answer'   => 'Hotel Benizia is at 1 Kingsley Emu Street, Central Area, Asaba 320242, Delta State, Nigeria — a central location with easy access to Asaba International Airport, government offices, and the city centre.',
                'sort'     => 10,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
