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
