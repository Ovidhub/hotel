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

// Static pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
