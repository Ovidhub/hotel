<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Apartment;
use App\Models\BlogPost;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticPages = [
            ['url' => route('home'),            'changefreq' => 'weekly',  'priority' => '1.0'],
            ['url' => route('rooms.index'),     'changefreq' => 'weekly',  'priority' => '0.8'],
            ['url' => route('apartments.index'),'changefreq' => 'weekly',  'priority' => '0.8'],
            ['url' => route('blog.index'),      'changefreq' => 'weekly',  'priority' => '0.8'],
            ['url' => route('restaurant'),      'changefreq' => 'monthly', 'priority' => '0.6'],
            ['url' => route('gallery'),         'changefreq' => 'monthly', 'priority' => '0.6'],
            ['url' => route('events'),          'changefreq' => 'monthly', 'priority' => '0.6'],
            ['url' => route('about'),           'changefreq' => 'monthly', 'priority' => '0.6'],
            ['url' => route('contact'),         'changefreq' => 'monthly', 'priority' => '0.6'],
            ['url' => route('faq'),             'changefreq' => 'monthly', 'priority' => '0.6'],
        ];

        $rooms = Room::where('is_active', true)->get()->map(function ($room) {
            return [
                'url'        => route('rooms.show', $room),
                'lastmod'    => $room->updated_at->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority'   => '0.7',
            ];
        });

        $apartments = Apartment::where('is_active', true)->get()->map(function ($apartment) {
            return [
                'url'        => route('apartments.show', $apartment),
                'lastmod'    => $apartment->updated_at->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority'   => '0.7',
            ];
        });

        $posts = BlogPost::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->get()
            ->map(function ($post) {
                return [
                    'url'        => route('blog.show', $post),
                    'lastmod'    => $post->updated_at->format('Y-m-d'),
                    'changefreq' => 'monthly',
                    'priority'   => '0.7',
                ];
            });

        $urls = collect($staticPages)
            ->concat($rooms)
            ->concat($apartments)
            ->concat($posts);

        $content = view('sitemap', compact('urls'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    public function robots(): \Illuminate\Http\Response
    {
        $canonical = config('hotel.canonical');

        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /checkout',
            'Disallow: /book',
            'Disallow: /booking',
            'Sitemap: ' . $canonical . '/sitemap.xml',
        ]) . "\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
}
