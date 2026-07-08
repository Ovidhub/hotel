# Black Tower Hotels — Theme Layer + Admin Integration

**Date:** 2026-07-08
**Status:** Approved (design), pending implementation plan
**Related:** deferred multi-tenant SaaS (this is the single-tenant "one app per hotel via themes" stepping-stone)

## Goal

Turn the existing Hotel Benizia Laravel app (mature admin + backend) into a codebase whose **public frontend is a swappable theme**, then add a second theme — **Black Tower Hotels Asaba** — that reproduces the live site at `https://blacktowerhotelsasaba.com/` (or better). Black Tower reuses the existing admin/backend unchanged and is deployed as its own site (own domain, own database).

This is "Approach A" (theme layer in one codebase). Multi-tenant SaaS is explicitly deferred.

## Context (what exists today)

- Laravel 11 + Blade + Tailwind 3 + Alpine + Vite, Pest tests. Shared cPanel hosting; deploy via SSH; build assets locally.
- Public views live flat under `resources/views/` (home.blade.php, rooms/, apartments/, restaurant.blade.php, contact.blade.php, partials/header|footer, components/…).
- Admin under `resources/views/admin/` + `App\Http\Controllers\Admin\*` with models: Room, Apartment, Booking, BlogPost, Testimonial, Faq, Message, PaymentMethod, Setting, AvailabilityBlock, IcalFeed.
- Site identity in `config/hotel.php` (name, phone, email, address, nav, etc.), currently hardcoded to Benizia.
- Design source for Black Tower: a Next.js project at `./blacktower-hotel/` (single `page.tsx`, `globals.css` ~1483 lines, a `BookingForm`, a drizzle `booking_inquiries` schema). It is a faithful single-page port of the live WordPress site — used as the visual reference, not as runnable code.

## Non-goals (out of scope)

- Multi-tenant / single-admin-many-hotels (deferred).
- Online payments / commitment fee / Paystack for Black Tower (inquiry form only).
- Apartments, restaurant, blog, events pages for Black Tower (hidden; nav is Home/About/Rooms/Contact).
- Rewriting or moving the admin, models, migrations, auth.
- Editing production `.env` files as part of code changes (per-deploy `.env` is set at deploy time).

## Architecture — Theme Layer

### Directory structure
```
resources/views/
  themes/
    benizia/        # current public frontend moved here
      layouts/app.blade.php
      partials/{header,footer,...}.blade.php
      components/...   # theme-specific components (section-intro, cta, video-tour, etc.)
      home.blade.php, about.blade.php, contact.blade.php, rooms/{index,show}.blade.php, ...
    blacktower/     # new theme
      layouts/app.blade.php
      partials/{header,footer}.blade.php
      home.blade.php, about.blade.php, contact.blade.php, rooms/{index,show}.blade.php
  admin/            # SHARED, unchanged
  auth/, emails/, errors/, components/schema/  # shared, unchanged
```

### Theme resolution
- `config('hotel.theme')` returns the active theme slug, read from `THEME` env (default `benizia`).
- A `ThemeServiceProvider` registers a Blade view namespace at boot:
  `View::addNamespace('theme', resource_path('views/themes/'.config('hotel.theme')))`.
- Public controllers render namespaced views: `view('home')` → `view('theme::home')`, layout `x-theme::layouts.app` (or `@extends('theme::layouts.app')`).
- Shared, non-theme views (admin, auth, emails, SEO/schema components) keep their current global references.
- Fallback: if a theme lacks a view, that is a build-time concern; each theme must provide the full set of public pages it links to.

### What is shared vs per-theme vs per-deploy
- **Shared (all themes):** models, controllers, migrations, admin UI, auth, mail, SEO/schema components, booking-inquiry backend.
- **Per-theme:** layout, header/footer, page templates, CSS/design tokens, theme assets/images.
- **Per-deploy (env/config + DB):** `THEME`, hotel name/phone/email/address, logo, seeded rooms/testimonials/settings, database.

### Hotel identity becomes env-driven
`config/hotel.php` reads identity from env with Benizia values as defaults, so Benizia's deploy is unchanged and Black Tower's deploy overrides via its own `.env`:
```php
'name'  => env('HOTEL_NAME', 'Hotel Benizia'),
'phone' => env('HOTEL_PHONE', '+234 813 406 2487'),
'phone_href' => env('HOTEL_PHONE_HREF', '+2348134062487'),
'email' => env('HOTEL_EMAIL', 'hotelbenizia66@gmail.com'),
'address' => env('HOTEL_ADDRESS', '1 Kingsley Emu Street, Central Area, Asaba 320242, Delta State'),
'theme' => env('THEME', 'benizia'),
```
Black Tower `.env` (set at deploy): `THEME=blacktower`, `HOTEL_NAME="Black Tower Hotels Asaba"`, `HOTEL_PHONE="+234 912 793 6399"`, `HOTEL_PHONE_HREF=+2349127936399`, `HOTEL_EMAIL=contact@blacktowerhotelsasaba.com`, `HOTEL_ADDRESS="78 Anwai Road, Asaba, Delta State, Nigeria"`.

## Black Tower theme

### Design tokens
- Colors: `--bt-red #e85f4c`, `--bt-red-strong #eb0607`, `--bt-dark #292836`, `--bt-text #6b6a71`, `--bt-cream #faf5ef`, `--bt-line #ebe7de`, `--bt-footer #1e1d28`.
- Fonts: Marcellus (headings), Urbanist (body) via Google Fonts.
- Port `blacktower-hotel/src/app/globals.css` into the theme's stylesheet (Tailwind app.css layer or a dedicated theme CSS compiled by Vite). Keep class names close to the source to speed porting.

### Pages (reuse existing controllers/models)
- **Home** (one page, anchored sections): header/nav (Home, About, Rooms, Contact) · hero ("Experience Comfort") · room-search/booking bar · about · **rooms** (from `Room` model, top 3) · why-choose-us (4 benefits) · service band · **gallery** (theme images) · highlights (cream) · counters · **testimonials** (from `Testimonial` model) · amenities + **booking inquiry form** · footer (contact + newsletter).
- **Rooms index** and **Room detail** — `RoomController@index/show`, styled in Black Tower design.
- **About** — static content in Black Tower style.
- **Contact** — existing `ContactController` + `Message`, styled; shows Black Tower phone/email/address/map.

### Assets/images
- Download `tour-*.png` gallery/room images and the logo (`blacklogowht.png`) from the live site into `public/img/themes/blacktower/` (or storage), optimize via the existing `ImageEnhancer`. Do not rely on `wp-content/uploads` URLs at runtime.

## Booking inquiry feature (shared backend, used by Black Tower)

Lightweight inquiry, mirroring the live site and the Next `BookingForm`.

- **Migration/table `booking_inquiries`:** id, name, email, phone (nullable), room, check_in, check_out, guests, message (nullable), status (new/handled, default new), timestamps.
- **Model:** `App\Models\BookingInquiry` (guarded=[]).
- **Public:** route `POST /booking-inquiry` → `BookingInquiryController@store` (validate, save, email the hotel, redirect back with success). Form lives in the theme.
- **Admin:** `Admin\BookingInquiryController@index/show/destroy` + views mirroring the existing Messages screen; sidebar link "Booking Inquiries". Shared across themes (Benizia can use it too, but not required).
- **Mail:** a `BookingInquiryReceived` mailable to `config('hotel.email')`.

## Data seeding

A `BlackTowerRoomSeeder` (run only on the Black Tower deploy) seeds rooms: Classic Room ₦75,000, Executive Room ₦95,000, Luxury Suite ₦125,000, Royal Apartment. Editable in the existing admin afterwards. Benizia's seeders unchanged.

## Deployment model

- One git repo. Two deployments:
  - Benizia (existing): `THEME=benizia`, unchanged.
  - Black Tower (new): separate cPanel site/domain `blacktowerhotelsasaba.com`, own MySQL DB, `.env` with `THEME=blacktower` + Black Tower identity, run migrations + `BlackTowerRoomSeeder`.
- Assets built locally (`npm run build`) and uploaded, per existing workflow. Theme CSS must be included in the Vite build.

## Testing

- Theme resolution: a test sets `THEME` and asserts the correct theme's home renders.
- Public pages for the active theme return 200 (home, rooms, about, contact).
- Booking inquiry: valid POST stores a row + returns success; invalid POST returns validation errors; admin (auth+admin) can view the inquiry list; non-admin gets 403.
- Existing Benizia tests must stay green after the view move (update any hardcoded view-path expectations).

## Risks / migration notes

- Moving Benizia's public views into `themes/benizia/` and switching controllers to `theme::` namespaces is the riskiest step; do it in one pass and run the full test suite. Watch for `@include`/`<x-...>` references that assume global paths.
- Vite must compile each theme's CSS; confirm the manifest includes theme assets before deploy (Tailwind arbitrary values need the source present at build time).
- Keep shared components (SEO, schema) theme-agnostic so both themes render valid meta.
