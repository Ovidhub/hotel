<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'slug'           => 'how-to-book-hotel-in-asaba',
                'title'          => 'How to book a hotel in Asaba for a stress-free experience',
                'excerpt'        => 'A simple guide to selecting your room, planning check-in, and confirming the services you need before arrival.',
                'body'           => "Booking a hotel in Asaba does not have to be complicated. Whether you are visiting for business, a family occasion, or simply seeking a comfortable base in Delta State, a little preparation goes a long way.\n\nStart by identifying your dates and the number of guests in your party. At Hotel Benizia, rooms range from the Deluxe Classic for solo or couple stays to the Penthouse Suite for VIP experiences. Once you know your dates, contact the reservations team via phone or the website booking form. A 40% commitment fee is required to secure your room while availability is confirmed.\n\nBefore arrival, confirm the check-in time (2:00 PM), clarify whether you need airport pickup, and ask about any event services if you are hosting a function. Hotel Benizia's front desk team is available 24 hours to assist with any pre-arrival questions. A smooth check-in starts with a clear booking.",
                'category'       => 'Booking Tips',
                'category_color' => null,
                'image'          => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/our-vision-550x412.jpg',
                'author'         => 'Hotel Benizia',
                'published_at'   => Carbon::createFromFormat('M d, Y', 'Jan 08, 2026'),
            ],
            [
                'slug'           => 'choosing-hotel-benizia-room-type',
                'title'          => 'A guide to choosing the right Hotel Benizia room type',
                'excerpt'        => 'Compare Deluxe Classic, Deluxe Premium, Alcove Room, Diplomatic Suite, and Penthouse Suite options.',
                'body'           => "Hotel Benizia offers five distinct room categories designed to match a wide range of guest needs. Choosing the right room means understanding what each type offers and how it aligns with your stay purpose.\n\nThe Deluxe Classic (NGN 30,000/night) is ideal for solo travelers or couples seeking a clean, comfortable stay with breakfast and pool access. The Deluxe Premium (NGN 40,000/night) adds more space and a premium feel, making it popular with business travelers. The Alcove Room (NGN 45,000/night) features a distinctive private alcove setting for guests who value a quieter, more intimate atmosphere.\n\nFor those seeking suite-level comfort, the Diplomatic Suite (NGN 80,000/night) offers executive space with a lounge feel, while the Penthouse Suite (NGN 180,000/night) delivers the ultimate luxury experience at Hotel Benizia. All rooms include complimentary breakfast, pool access, and gym access. The higher categories add butler service, priority support, and enhanced privacy.",
                'category'       => 'Luxury Rooms',
                'category_color' => null,
                'image'          => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/stay-upfront-550x413.jpg',
                'author'         => 'Hotel Benizia',
                'published_at'   => Carbon::createFromFormat('M d, Y', 'Jan 10, 2026'),
            ],
            [
                'slug'           => 'hotel-amenities-that-improve-your-stay',
                'title'          => 'How hotel amenities transform your stay',
                'excerpt'        => 'From breakfast and swimming pool access to restaurant, bar, events, power, and security, amenities shape the experience.',
                'body'           => "The difference between a forgettable hotel night and a genuinely memorable stay often comes down to amenities. At Hotel Benizia in Asaba, the facilities are designed to make every part of your visit comfortable and enjoyable.\n\nAll rooms include complimentary breakfast, high-speed WiFi, pool access, and gym access. These are not extras — they are standard to every guest's experience. Beyond the rooms, the 24-hour restaurant and bar serves both Nigerian and continental cuisine, so you are never without good food regardless of the hour. The swimming pool and bar area is a popular spot for evening relaxation.\n\nFor events, Hotel Benizia's air-conditioned event halls and boardroom can accommodate weddings, conferences, product launches, and private gatherings. Reliable 24-hour power supply and a trained security team ensure guests feel safe and comfortable throughout their stay. From breakfast to check-out, every amenity at Hotel Benizia is designed to support a complete, uninterrupted guest experience.",
                'category'       => 'Hotel Amenities',
                'category_color' => null,
                'image'          => 'https://hotelbenizia.ng/wp-content/uploads/2025/06/other-services-780x521.jpg',
                'author'         => 'Hotel Benizia',
                'published_at'   => Carbon::createFromFormat('M d, Y', 'Jan 12, 2026'),
            ],
            [
                'slug'           => 'hotel-booking-tips',
                'title'          => 'How to Book a Hotel: Tips for a Hassle-Free Experience',
                'excerpt'        => 'Planning a trip to Asaba? Here are our top tips to ensure your hotel booking goes smoothly and you get the best value for your stay.',
                'body'           => "Planning a hotel stay in Asaba is easy when you know what to look for. The key is to think ahead, communicate clearly with the hotel team, and confirm your requirements before arrival.\n\nFirst, decide on your travel dates and budget. Hotel Benizia offers rooms from NGN 30,000 per night, with options scaling up to the Penthouse Suite. Knowing your budget first helps narrow your choice quickly. Second, check what is included — at Hotel Benizia, breakfast, pool access, and gym access come standard with every room booking.\n\nWhen you are ready to book, contact the reservations team and prepare to pay a 40% commitment fee. This secures your room while the team confirms availability. Keep your payment confirmation ready and arrive at the agreed check-in time (2:00 PM). If you need airport pickup or early check-in, request these during the booking call to avoid any inconvenience.",
                'category'       => 'Booking Tips',
                'category_color' => 'bg-[#1D5C52]',
                'image'          => 'https://images.pexels.com/photos/13114682/pexels-photo-13114682.jpeg?auto=compress&cs=tinysrgb&w=700',
                'author'         => 'Amaka Eze',
                'published_at'   => Carbon::createFromFormat('M d, Y', 'Jan 12, 2025'),
            ],
            [
                'slug'           => 'dining-at-benizia',
                'title'          => 'A Culinary Journey: Dining at Hotel Benizia',
                'excerpt'        => "From rich Nigerian pepper soup to fine continental dishes, our 24/7 restaurant promises a gastronomic adventure every time you visit.",
                'body'           => "Food is an essential part of any hotel experience, and at Hotel Benizia, the kitchen never closes. The 24-hour restaurant and bar serves a carefully curated menu of Nigerian and continental dishes, prepared by experienced chefs committed to quality and taste.\n\nStart your morning with the Chef Breakfast Platter — fresh eggs, toast, sausage, fruit, and your choice of tea or coffee. For lunch or dinner, the Delta Pepper Soup made with fresh local broth is a guest favourite, as is the Jollof Rice Royale served with chicken or beef. The Grilled Fish and Chips is a poolside staple for relaxed afternoon meals.\n\nThe bar is open around the clock, offering cocktails, mocktails, and a curated drink selection. Whether you are winding down after a long business day or celebrating a special occasion, Hotel Benizia's dining experience is designed to satisfy every palate in Asaba.",
                'category'       => 'Dining',
                'category_color' => 'bg-[#C9A96E]',
                'image'          => 'https://images.pexels.com/photos/27612507/pexels-photo-27612507.jpeg?auto=compress&cs=tinysrgb&w=700',
                'author'         => 'Amaka Eze',
                'published_at'   => Carbon::createFromFormat('M d, Y', 'Dec 28, 2024'),
            ],
            [
                'slug'           => 'events-at-benizia',
                'title'          => "Why Hotel Benizia is Asaba's Premier Event Venue",
                'excerpt'        => "From intimate corporate boardrooms to grand wedding receptions, our versatile event spaces and dedicated team make every occasion special.",
                'body'           => "Hotel Benizia has established itself as one of Asaba's most sought-after event venues, offering flexible spaces and a dedicated hospitality team for every type of function.\n\nThe hotel's air-conditioned event halls can accommodate both intimate gatherings and large-scale celebrations. Whether you are planning a wedding reception, a corporate conference, a product launch, or a private birthday, the Hall team works closely with clients to ensure every detail is handled professionally. Catering services from the 24-hour restaurant are available for all events.\n\nFor business functions, the boardroom provides a focused, professional setting with reliable power, high-speed WiFi, and audio-visual support. Hotel Benizia's location in Asaba, off Summit Road, makes it convenient for guests traveling from across Delta State and beyond. If you are planning an event in Asaba, Hotel Benizia offers the space, service, and experience to make it memorable.",
                'category'       => 'Events',
                'category_color' => 'bg-[#1D5C52]',
                'image'          => 'https://images.pexels.com/photos/2291624/pexels-photo-2291624.jpeg?auto=compress&cs=tinysrgb&w=700',
                'author'         => 'Ngozi Adeleke',
                'published_at'   => Carbon::createFromFormat('M d, Y', 'Dec 15, 2024'),
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::create($post);
        }
    }
}
