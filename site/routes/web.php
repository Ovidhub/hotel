<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rooms
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

// Apartments
Route::get('/apartments', [ApartmentController::class, 'index'])->name('apartments.index');
Route::get('/apartments/{apartment}', [ApartmentController::class, 'show'])->name('apartments.show');

// Restaurant
Route::get('/restaurant', [RestaurantController::class, 'index'])->name('restaurant');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

// Events
Route::get('/events', [EventController::class, 'index'])->name('events');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

// Booking
Route::get('/book/{type}/{slug}', [BookingController::class, 'create'])->name('booking.create');
Route::post('/book', [BookingController::class, 'store'])->name('booking.store');

// Checkout flow
Route::get('/checkout/{booking:ref}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/{booking:ref}', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
Route::get('/booking/success/{booking:ref}', [BookingController::class, 'success'])->name('booking.success');

// Paystack payment gateway
Route::post('/paystack/init/{booking:ref}', [PaystackController::class, 'init'])->name('paystack.init');
Route::get('/paystack/callback', [PaystackController::class, 'callback'])->name('paystack.callback');

// Static pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

// ── Breeze: dashboard redirect (admins → /admin, others → home) ──────────────
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

// ── Breeze: profile routes (auth users) ──────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Admin area (auth + admin middleware) ──────────────────────────────────────
// Public availability calendar feed (.ics) consumed by Booking.com / OTAs.
Route::get('calendar/{token}.ics', [\App\Http\Controllers\CalendarController::class, 'show'])
    ->where('token', '[A-Za-z0-9]+')
    ->name('calendar.ical');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);
    Route::resource('apartments', \App\Http\Controllers\Admin\ApartmentController::class);
    Route::resource('payment-methods', \App\Http\Controllers\Admin\PaymentMethodController::class)
         ->parameters(['payment-methods' => 'paymentMethod']);

    // Availability (calendar / blocked dates per room & apartment)
    Route::get('availability', [\App\Http\Controllers\Admin\AvailabilityController::class, 'index'])->name('availability.index');
    Route::get('availability/{type}/{id}', [\App\Http\Controllers\Admin\AvailabilityController::class, 'show'])->name('availability.show');
    Route::post('availability/{type}/{id}/blocks', [\App\Http\Controllers\Admin\AvailabilityController::class, 'storeBlock'])->name('availability.blocks.store');
    Route::delete('availability/blocks/{block}', [\App\Http\Controllers\Admin\AvailabilityController::class, 'destroyBlock'])->name('availability.blocks.destroy');
    // Booking.com / OTA iCal import feeds
    Route::post('availability/{type}/{id}/feeds', [\App\Http\Controllers\Admin\AvailabilityController::class, 'storeFeed'])->name('availability.feeds.store');
    Route::post('availability/feeds/{feed}/sync', [\App\Http\Controllers\Admin\AvailabilityController::class, 'syncFeed'])->name('availability.feeds.sync');
    Route::delete('availability/feeds/{feed}', [\App\Http\Controllers\Admin\AvailabilityController::class, 'destroyFeed'])->name('availability.feeds.destroy');

    // Bookings (no create/store — bookings come from guests)
    Route::get('bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking:ref}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::put('bookings/{booking:ref}', [\App\Http\Controllers\Admin\BookingController::class, 'update'])->name('bookings.update');

    // Blog
    Route::resource('blog', \App\Http\Controllers\Admin\BlogController::class);

    // Testimonials
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class);

    // FAQs
    Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class);

    // Messages (index, show, destroy — no create/edit)
    Route::get('messages', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'show'])->name('messages.show');
    Route::delete('messages/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'destroy'])->name('messages.destroy');
});

// ── Breeze auth routes (login, register, logout, password reset, etc.) ────────
require __DIR__.'/auth.php';
