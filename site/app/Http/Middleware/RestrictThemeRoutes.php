<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * When a non-default theme is active (config('hotel.theme') !== 'default'),
 * the public routes that theme does not implement return 404 — so a themed
 * deploy (e.g. Black Tower) never serves the default (Benizia) fallback view
 * for features it doesn't offer. Benizia (default theme) is unaffected.
 */
class RestrictThemeRoutes
{
    /** Public route names not offered by non-default themes. */
    protected array $blocked = [
        'restaurant',
        'gallery',
        'events',
        'faq',
        'apartments.index',
        'apartments.show',
        'blog.index',
        'blog.show',
        'booking.create',
        'booking.store',
        'booking.success',
        'checkout.show',
        'checkout.confirm',
        'paystack.init',
        'paystack.callback',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (config('hotel.theme', 'default') !== 'default') {
            $name = optional($request->route())->getName();

            if ($name !== null && in_array($name, $this->blocked, true)) {
                abort(404);
            }
        }

        return $next($request);
    }
}
