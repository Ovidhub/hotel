<?php
namespace Database\Seeders;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlackTowerRoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['Classic Room', 75000, 2, 'A calm, spotless room designed for restorative nights and effortless short stays.'],
            ['Executive Room', 95000, 2, 'Extra space, premium bedding, and a refined setting for business or leisure trips.'],
            ['Luxury Suite', 125000, 4, 'Elegant suite comfort with a private lounge feel and attentive service throughout.'],
            ['Royal Apartment', 150000, 4, 'Our most spacious stay — apartment-style comfort with full hotel service.'],
        ];
        foreach ($rooms as $i => [$name, $price, $guests, $excerpt]) {
            Room::updateOrCreate(['slug' => Str::slug($name)], [
                'name' => $name, 'category' => 'Room', 'price' => $price,
                'price_label' => 'NGN '.number_format($price), 'guests' => $guests,
                'beds' => 1, 'excerpt' => $excerpt, 'description' => $excerpt,
                'size' => '25 sqm', 'image' => '/img/rooms/default.webp',
                'gallery' => [], 'amenities' => [], 'includes' => [], 'policies' => [],
                'is_active' => true, 'sort' => $i,
            ]);
        }
    }
}
