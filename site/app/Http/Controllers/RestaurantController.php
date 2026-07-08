<?php

namespace App\Http\Controllers;

class RestaurantController extends Controller
{
    public function index()
    {
        $menu = [
            [
                'category' => 'Breakfast',
                'items' => [
                    ['name' => 'Chef Breakfast Platter',   'description' => 'Fresh eggs, toast, sausage, fruit, and your choice of tea or coffee.',   'price' => 'NGN 4,500'],
                    ['name' => 'Continental Breakfast',    'description' => 'Croissant, jam, juice, yogurt, and seasonal fruit.',                       'price' => 'NGN 3,500'],
                    ['name' => 'Nigerian Breakfast Bowl',  'description' => 'Akara, ogi, and fried plantain served with a chilled drink.',              'price' => 'NGN 3,000'],
                ],
            ],
            [
                'category' => 'Mains',
                'items' => [
                    ['name' => 'Delta Pepper Soup',        'description' => 'Freshly prepared local broth with your choice of fish, goat, or chicken.', 'price' => 'NGN 5,500'],
                    ['name' => 'Jollof Rice Royale',       'description' => 'Smoky party-style jollof rice served with grilled chicken or beef.',        'price' => 'NGN 6,000'],
                    ['name' => 'Grilled Fish & Chips',     'description' => 'Seasoned whole tilapia grilled to order, served with crispy fries.',        'price' => 'NGN 7,500'],
                    ['name' => 'Egusi & Pounded Yam',      'description' => 'Slow-cooked egusi soup with assorted meat, served with pounded yam.',       'price' => 'NGN 6,500'],
                    ['name' => 'Pasta Primavera',          'description' => 'Fettuccine in creamy tomato sauce with grilled vegetables.',                 'price' => 'NGN 5,000'],
                ],
            ],
            [
                'category' => 'Bar & Drinks',
                'items' => [
                    ['name' => 'Signature Cocktail',       'description' => 'Benizia blend — citrus, ginger, and premium spirits.',                      'price' => 'NGN 4,000'],
                    ['name' => 'Fresh Tropical Juice',     'description' => 'Mango, pineapple, or watermelon — freshly pressed daily.',                  'price' => 'NGN 1,500'],
                    ['name' => 'Chapman Mocktail',         'description' => 'Classic Nigerian party mocktail with bitters and citrus.',                   'price' => 'NGN 2,000'],
                ],
            ],
        ];

        return view(theme_view('restaurant'), compact('menu'));
    }
}
