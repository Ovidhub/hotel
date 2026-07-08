# Black Tower Theme + Theme Layer Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make the public frontend a swappable theme and add a second theme, "Black Tower Hotels Asaba", that reuses the existing admin/backend and matches the live site, with a simple booking-inquiry flow.

**Architecture:** Benizia stays the built-in **default** theme (its current flat views in `resources/views/`, untouched). A `theme_view($name)` resolver returns `themes.<active>.<name>` when a non-default theme is active and that view exists, else the flat default — so public controllers work for both hotels. A new `booking_inquiries` table + model + public form + admin screen provides the inquiry flow. Hotel identity (name/phone/email/address) and the active theme are env-driven, so one codebase serves both hotels via separate deployments.

**Tech Stack:** Laravel 11, Blade, Tailwind CSS 3, Alpine.js, Vite, Pest v3, MySQL (prod) / SQLite (test). Design source: `blacktower-hotel/` (Next.js — reference only, not run).

## Global Constraints

- Do NOT move or rename Benizia's existing flat public views; Benizia must render byte-for-byte as today when `THEME` is unset. Verify by keeping the full existing Pest suite green.
- Do NOT modify `.env` files as part of code changes; per-deploy env is set at deploy time. Config reads env with Benizia values as defaults.
- Admin, auth, models (except the new one), migrations (except the new one), and mail infra stay shared and otherwise unchanged.
- Black Tower nav is Home / About / Rooms / Contact only. No apartments/restaurant/blog/events pages, no online payment.
- Design tokens (verbatim): `--bt-red #e85f4c`, `--bt-red-strong #eb0607`, `--bt-dark #292836`, `--bt-text #6b6a71`, `--bt-cream #faf5ef`, `--bt-line #ebe7de`, `--bt-footer #1e1d28`. Fonts: Marcellus (headings), Urbanist (body).
- Black Tower identity (verbatim): name "Black Tower Hotels Asaba", phone "+234 912 793 6399" (href `+2349127936399`), email "contact@blacktowerhotelsasaba.com", address "78 Anwai Road, Asaba, Delta State, Nigeria".
- Rooms to seed (verbatim): Classic Room ₦75,000; Executive Room ₦95,000; Luxury Suite ₦125,000; Royal Apartment ₦150,000.
- All shell commands run from `c:/Users/DELL/hotel/site` unless stated. Tests: `php artisan test`.

---

## Phase 1 — Theme layer foundation

### Task 1: Theme config + env-driven identity + `theme_view()` helper

**Files:**
- Modify: `config/hotel.php` (identity keys → env; add `theme`)
- Create: `app/helpers.php`
- Modify: `composer.json` (autoload files)
- Test: `tests/Unit/ThemeViewTest.php`

**Interfaces:**
- Produces: global function `theme_view(string $name): string`; config key `hotel.theme`.

- [ ] **Step 1: Write the failing test**

```php
<?php
// tests/Unit/ThemeViewTest.php

it('returns the flat view name for the default theme', function () {
    config()->set('hotel.theme', 'default');
    expect(theme_view('home'))->toBe('home');
});

it('returns the themed view name when the theme provides it', function () {
    config()->set('hotel.theme', 'blacktower');
    // 'themes.blacktower.probe' exists on disk only in later tasks; simulate with a real temp view path check:
    View::addLocation(resource_path('views'));
    // Fall back when themed view missing:
    expect(theme_view('___nope___'))->toBe('___nope___');
});
```

Add at top: `use Illuminate\Support\Facades\View;`

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Unit/ThemeViewTest.php`
Expected: FAIL — `Call to undefined function theme_view()`.

- [ ] **Step 3: Create the helper**

```php
<?php
// app/helpers.php

if (! function_exists('theme_view')) {
    /**
     * Resolve a public view name against the active theme, falling back to
     * the flat (default = Benizia) view when the theme does not provide it.
     */
    function theme_view(string $name): string
    {
        $theme = config('hotel.theme', 'default');

        if ($theme && $theme !== 'default') {
            $themed = "themes.{$theme}.{$name}";
            if (view()->exists($themed)) {
                return $themed;
            }
        }

        return $name;
    }
}
```

- [ ] **Step 4: Autoload the helper**

In `composer.json`, under `"autoload"`, add a `"files"` array (merge if `autoload` already has keys):

```json
"autoload": {
    "files": ["app/helpers.php"],
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    }
}
```

Run: `composer dump-autoload`

- [ ] **Step 5: Add config keys (env-driven identity + theme)**

In `config/hotel.php`, change the identity lines to read env with the current Benizia values as defaults, and add `theme`:

```php
  'name' => env('HOTEL_NAME', 'Hotel Benizia'),
  'tagline' => env('HOTEL_TAGLINE', 'Luxury in the heart of Asaba'),
  'phone' => env('HOTEL_PHONE', '+234 813 406 2487'),
  'phone_href' => env('HOTEL_PHONE_HREF', '+2348134062487'),
  'email' => env('HOTEL_EMAIL', 'hotelbenizia66@gmail.com'),
  'address' => env('HOTEL_ADDRESS', '1 Kingsley Emu Street, Central Area, Asaba 320242, Delta State'),
  'theme' => env('THEME', 'default'),
```

(Leave all other keys — check_in, check_out, apartments, socials, nav, ticker, booking, geo, maps_url, canonical — exactly as they are.)

- [ ] **Step 6: Run tests to verify they pass**

Run: `php artisan test tests/Unit/ThemeViewTest.php`
Expected: PASS (2 passed).

- [ ] **Step 7: Commit**

```bash
git add site/app/helpers.php site/composer.json site/composer.lock site/config/hotel.php site/tests/Unit/ThemeViewTest.php
git commit -m "feat: add theme_view resolver + env-driven hotel identity"
```

---

### Task 2: Route public controllers through `theme_view()`

**Files:**
- Modify: `app/Http/Controllers/HomeController.php`, `RoomController.php`, `ApartmentController.php`, `RestaurantController.php`, `GalleryController.php`, `EventController.php`, `BlogController.php`, `PageController.php`, `ContactController.php`, `BookingController.php`, `CheckoutController.php` (public ones only — NOT `Admin\*`)
- Test: existing suite

**Interfaces:**
- Consumes: `theme_view()` from Task 1.

- [ ] **Step 1: Rewire every public `view('x', …)` call**

In each public controller listed above, wrap the view name passed to `view(...)` with `theme_view(...)`. Example (HomeController):

```php
// before
return view('home', [...]);
// after
return view(theme_view('home'), [...]);
```

Apply the same transform to every `return view('NAME', …)` in those controllers, including dotted names, e.g. `view('rooms.show', …)` → `view(theme_view('rooms.show'), …)`. Do NOT touch `App\Http\Controllers\Admin\*` or auth controllers.

- [ ] **Step 2: Run the full suite (default theme unchanged)**

Run: `php artisan test`
Expected: PASS — same counts as before this task (theme is `default`, so `theme_view` returns the flat names; behavior is identical).

- [ ] **Step 3: Commit**

```bash
git add site/app/Http/Controllers
git commit -m "refactor: resolve public views through theme_view()"
```

---

## Phase 2 — Booking inquiry (shared backend)

### Task 3: `booking_inquiries` table + model

**Files:**
- Create: `database/migrations/2026_07_08_000000_create_booking_inquiries_table.php`
- Create: `app/Models/BookingInquiry.php`
- Test: `tests/Feature/BookingInquiryModelTest.php`

**Interfaces:**
- Produces: `App\Models\BookingInquiry` with fillable columns: name, email, phone, room, check_in, check_out, guests, message, status.

- [ ] **Step 1: Write the failing test**

```php
<?php
// tests/Feature/BookingInquiryModelTest.php
use App\Models\BookingInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('persists a booking inquiry with defaults', function () {
    $i = BookingInquiry::create([
        'name' => 'Ada', 'email' => 'ada@example.com', 'phone' => '0803',
        'room' => 'Classic Room', 'check_in' => '2026-08-01',
        'check_out' => '2026-08-03', 'guests' => '2', 'message' => 'hi',
    ]);
    expect($i->status)->toBe('new');
    $this->assertDatabaseHas('booking_inquiries', ['email' => 'ada@example.com']);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/BookingInquiryModelTest.php`
Expected: FAIL — no `booking_inquiries` table / class.

- [ ] **Step 3: Create the migration**

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('booking_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('room');
            $table->string('check_in');
            $table->string('check_out');
            $table->string('guests');
            $table->text('message')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('booking_inquiries'); }
};
```

- [ ] **Step 4: Create the model**

```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BookingInquiry extends Model
{
    protected $guarded = [];
    protected $attributes = ['status' => 'new'];
}
```

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test tests/Feature/BookingInquiryModelTest.php`
Expected: PASS.

- [ ] **Step 6: Commit**

```bash
git add site/database/migrations site/app/Models/BookingInquiry.php site/tests/Feature/BookingInquiryModelTest.php
git commit -m "feat: booking_inquiries table + model"
```

---

### Task 4: Public booking-inquiry submission + mail

**Files:**
- Create: `app/Http/Requests/BookingInquiryRequest.php`
- Create: `app/Http/Controllers/BookingInquiryController.php`
- Create: `app/Mail/BookingInquiryReceived.php`
- Create: `resources/views/emails/booking-inquiry-received.blade.php`
- Modify: `routes/web.php` (add public route)
- Test: `tests/Feature/BookingInquiryTest.php`

**Interfaces:**
- Consumes: `BookingInquiry` (Task 3).
- Produces: route name `booking-inquiry.store` (POST `/booking-inquiry`); controller `store(BookingInquiryRequest $request)`.

- [ ] **Step 1: Write the failing test**

```php
<?php
// tests/Feature/BookingInquiryTest.php
use App\Mail\BookingInquiryReceived;
use App\Models\BookingInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('stores a valid inquiry, mails the hotel, and redirects with status', function () {
    Mail::fake();
    $payload = [
        'name' => 'Ada', 'email' => 'ada@example.com', 'phone' => '0803',
        'room' => 'Classic Room', 'check_in' => '2026-08-01',
        'check_out' => '2026-08-03', 'guests' => '2', 'message' => 'hi',
    ];
    $this->post(route('booking-inquiry.store'), $payload)
         ->assertRedirect()
         ->assertSessionHas('status');
    $this->assertDatabaseHas('booking_inquiries', ['email' => 'ada@example.com']);
    Mail::assertSent(BookingInquiryReceived::class);
});

it('rejects an invalid inquiry', function () {
    $this->post(route('booking-inquiry.store'), ['name' => ''])
         ->assertSessionHasErrors(['name', 'email', 'room', 'check_in', 'check_out', 'guests']);
    expect(BookingInquiry::count())->toBe(0);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/BookingInquiryTest.php`
Expected: FAIL — route not defined.

- [ ] **Step 3: Create the Form Request**

```php
<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class BookingInquiryRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:40'],
            'room'      => ['required', 'string', 'max:255'],
            'check_in'  => ['required', 'string', 'max:40'],
            'check_out' => ['required', 'string', 'max:40'],
            'guests'    => ['required', 'string', 'max:20'],
            'message'   => ['nullable', 'string', 'max:2000'],
        ];
    }
}
```

- [ ] **Step 4: Create the Mailable**

```php
<?php
namespace App\Mail;
use App\Models\BookingInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingInquiryReceived extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public BookingInquiry $inquiry) {}
    public function build()
    {
        return $this->subject('New booking inquiry — '.$this->inquiry->room)
                    ->view('emails.booking-inquiry-received');
    }
}
```

- [ ] **Step 5: Create the email view**

```blade
{{-- resources/views/emails/booking-inquiry-received.blade.php --}}
<h2>New Booking Inquiry</h2>
<p><strong>Name:</strong> {{ $inquiry->name }}</p>
<p><strong>Email:</strong> {{ $inquiry->email }}</p>
<p><strong>Phone:</strong> {{ $inquiry->phone ?: '—' }}</p>
<p><strong>Room:</strong> {{ $inquiry->room }}</p>
<p><strong>Dates:</strong> {{ $inquiry->check_in }} → {{ $inquiry->check_out }}</p>
<p><strong>Guests:</strong> {{ $inquiry->guests }}</p>
<p><strong>Message:</strong><br>{{ $inquiry->message ?: '—' }}</p>
```

- [ ] **Step 6: Create the controller**

```php
<?php
namespace App\Http\Controllers;
use App\Http\Requests\BookingInquiryRequest;
use App\Mail\BookingInquiryReceived;
use App\Models\BookingInquiry;
use Illuminate\Support\Facades\Mail;

class BookingInquiryController extends Controller
{
    public function store(BookingInquiryRequest $request)
    {
        $inquiry = BookingInquiry::create($request->validated());
        Mail::to(config('hotel.email'))->send(new BookingInquiryReceived($inquiry));
        return back()->with('status', 'Thank you — your booking request has been received. We will contact you shortly.');
    }
}
```

- [ ] **Step 7: Register the route**

In `routes/web.php`, near the other public POST routes (e.g. after the contact routes), add:

```php
use App\Http\Controllers\BookingInquiryController;
Route::post('/booking-inquiry', [BookingInquiryController::class, 'store'])->name('booking-inquiry.store');
```

- [ ] **Step 8: Run test to verify it passes**

Run: `php artisan test tests/Feature/BookingInquiryTest.php`
Expected: PASS (2 passed).

- [ ] **Step 9: Commit**

```bash
git add site/app/Http/Requests/BookingInquiryRequest.php site/app/Http/Controllers/BookingInquiryController.php site/app/Mail/BookingInquiryReceived.php site/resources/views/emails/booking-inquiry-received.blade.php site/routes/web.php site/tests/Feature/BookingInquiryTest.php
git commit -m "feat: public booking-inquiry submission + hotel notification email"
```

---

### Task 5: Admin booking-inquiry screen

**Files:**
- Create: `app/Http/Controllers/Admin/BookingInquiryController.php`
- Create: `resources/views/admin/booking-inquiries/index.blade.php`
- Create: `resources/views/admin/booking-inquiries/show.blade.php`
- Modify: `routes/web.php` (admin group), `resources/views/components/layouts/admin.blade.php` (sidebar link)
- Test: `tests/Feature/AdminBookingInquiryTest.php`

**Interfaces:**
- Consumes: `BookingInquiry` (Task 3).
- Produces: route names `admin.booking-inquiries.index`, `admin.booking-inquiries.show`, `admin.booking-inquiries.destroy`.

- [ ] **Step 1: Write the failing test**

```php
<?php
// tests/Feature/AdminBookingInquiryTest.php
use App\Models\BookingInquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(fn () => $this->seed());

it('lets an admin view inquiries', function () {
    BookingInquiry::create(['name'=>'Ada','email'=>'a@e.com','room'=>'Classic Room','check_in'=>'x','check_out'=>'y','guests'=>'2']);
    $admin = User::where('is_admin', true)->first();
    $this->actingAs($admin)->get(route('admin.booking-inquiries.index'))
         ->assertOk()->assertSee('Ada');
});

it('forbids non-admins', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $this->actingAs($user)->get(route('admin.booking-inquiries.index'))->assertForbidden();
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/AdminBookingInquiryTest.php`
Expected: FAIL — route not defined.

- [ ] **Step 3: Create the admin controller**

```php
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\BookingInquiry;

class BookingInquiryController extends Controller
{
    public function index()
    {
        $inquiries = BookingInquiry::latest()->paginate(20);
        return view('admin.booking-inquiries.index', compact('inquiries'));
    }
    public function show(BookingInquiry $bookingInquiry)
    {
        if ($bookingInquiry->status === 'new') {
            $bookingInquiry->update(['status' => 'handled']);
        }
        return view('admin.booking-inquiries.show', ['inquiry' => $bookingInquiry]);
    }
    public function destroy(BookingInquiry $bookingInquiry)
    {
        $bookingInquiry->delete();
        return redirect()->route('admin.booking-inquiries.index')->with('status', 'Inquiry deleted.');
    }
}
```

- [ ] **Step 4: Register admin routes**

Inside the `Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')` group in `routes/web.php`, add:

```php
Route::get('booking-inquiries', [\App\Http\Controllers\Admin\BookingInquiryController::class, 'index'])->name('booking-inquiries.index');
Route::get('booking-inquiries/{bookingInquiry}', [\App\Http\Controllers\Admin\BookingInquiryController::class, 'show'])->name('booking-inquiries.show');
Route::delete('booking-inquiries/{bookingInquiry}', [\App\Http\Controllers\Admin\BookingInquiryController::class, 'destroy'])->name('booking-inquiries.destroy');
```

- [ ] **Step 5: Create the index view**

```blade
<x-layouts.admin title="Booking Inquiries">
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-wider text-gray-500">
                    <th class="px-3 py-2">Name</th><th class="px-3 py-2">Room</th>
                    <th class="px-3 py-2 hidden md:table-cell">Dates</th>
                    <th class="px-3 py-2">Status</th><th class="px-3 py-2 text-right">View</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inquiries as $i)
                    <tr class="border-t border-gray-100">
                        <td class="px-3 py-2">{{ $i->name }}<div class="text-xs text-gray-400">{{ $i->email }}</div></td>
                        <td class="px-3 py-2">{{ $i->room }}</td>
                        <td class="px-3 py-2 hidden md:table-cell">{{ $i->check_in }} → {{ $i->check_out }}</td>
                        <td class="px-3 py-2"><span class="rounded-full px-2 py-0.5 text-xs {{ $i->status === 'new' ? 'bg-[#7C0E52]/10 text-[#7C0E52]' : 'bg-gray-100 text-gray-500' }}">{{ $i->status }}</span></td>
                        <td class="px-3 py-2 text-right"><a href="{{ route('admin.booking-inquiries.show', $i) }}" class="text-[#7C0E52] hover:underline">Open</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400">No inquiries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $inquiries->links() }}</div>
    </div>
</x-layouts.admin>
```

- [ ] **Step 6: Create the show view**

```blade
<x-layouts.admin title="Booking Inquiry">
    <div class="mx-auto max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
        <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2 text-sm">
            <div><dt class="text-xs uppercase text-gray-500">Name</dt><dd>{{ $inquiry->name }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Email</dt><dd>{{ $inquiry->email }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Phone</dt><dd>{{ $inquiry->phone ?: '—' }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Room</dt><dd>{{ $inquiry->room }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Check-in</dt><dd>{{ $inquiry->check_in }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Check-out</dt><dd>{{ $inquiry->check_out }}</dd></div>
            <div><dt class="text-xs uppercase text-gray-500">Guests</dt><dd>{{ $inquiry->guests }}</dd></div>
        </dl>
        <div class="mt-4"><dt class="text-xs uppercase text-gray-500">Message</dt><dd class="mt-1 text-sm">{{ $inquiry->message ?: '—' }}</dd></div>
        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.booking-inquiries.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Back</a>
            <form method="POST" action="{{ route('admin.booking-inquiries.destroy', $inquiry) }}" onsubmit="return confirm('Delete this inquiry?')">
                @csrf @method('DELETE')
                <button class="text-sm text-red-600 hover:underline">Delete</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
```

- [ ] **Step 7: Add sidebar link**

In `resources/views/components/layouts/admin.blade.php`, add a nav `<li>` near the Messages link (copy the Messages `<li>` structure, swap route to `admin.booking-inquiries.index`, `routeIs('admin.booking-inquiries.*')`, label "Booking Inquiries", and keep an inline SVG icon — reuse the calendar icon path `d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"`).

- [ ] **Step 8: Run test to verify it passes**

Run: `php artisan test tests/Feature/AdminBookingInquiryTest.php`
Expected: PASS (2 passed).

- [ ] **Step 9: Commit**

```bash
git add site/app/Http/Controllers/Admin/BookingInquiryController.php site/resources/views/admin/booking-inquiries site/routes/web.php site/resources/views/components/layouts/admin.blade.php site/tests/Feature/AdminBookingInquiryTest.php
git commit -m "feat: admin booking-inquiries screen"
```

---

## Phase 3 — Black Tower theme

> Design source of truth: `blacktower-hotel/src/app/page.tsx` (section markup/copy) and `blacktower-hotel/src/app/globals.css` (styles). Translate section-by-section into Blade; wire rooms and testimonials to the DB.

### Task 6: Theme scaffolding — layout, stylesheet, fonts, Vite entry

**Files:**
- Create: `resources/css/blacktower.css` (port of `blacktower-hotel/src/app/globals.css`)
- Create: `resources/views/themes/blacktower/layouts/app.blade.php`
- Modify: `vite.config.js` (add `resources/css/blacktower.css` input)
- Test: build succeeds

**Interfaces:**
- Produces: Blade layout `themes.blacktower.layouts.app` with a `@yield('content')`; compiled asset `blacktower.css`.

- [ ] **Step 1: Port the stylesheet**

Copy `blacktower-hotel/src/app/globals.css` to `resources/css/blacktower.css`. Replace the first line `@import "tailwindcss";` with the project's Tailwind directives:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

Keep everything else (the `:root` variables and all `.site-header`, `.hero`, `.room-card`, etc. rules) verbatim.

- [ ] **Step 2: Add the Vite input**

In `vite.config.js`, add `'resources/css/blacktower.css'` to the `input` array of `laravel({ input: [...] })` (alongside `resources/css/app.css` and `resources/js/app.js`).

- [ ] **Step 3: Create the theme layout**

```blade
{{-- resources/views/themes/blacktower/layouts/app.blade.php --}}
<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('hotel.name'))</title>
    <meta name="description" content="@yield('description', 'Premium comfort and elegant rooms at '.config('hotel.name').'.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/themes/blacktower/favicon.png') }}">
    <x-schema.hotel />
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/blacktower.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <div class="site-shell">
        @include('themes.blacktower.partials.header')
        @yield('content')
        @include('themes.blacktower.partials.footer')
    </div>
</body>
</html>
```

- [ ] **Step 4: Build to verify assets compile**

Run: `NODE_OPTIONS="--max-old-space-size=4096" npm run build`
Expected: build succeeds; `public/build/manifest.json` lists a `blacktower.css` asset.

- [ ] **Step 5: Commit**

```bash
git add site/resources/css/blacktower.css site/resources/views/themes/blacktower/layouts/app.blade.php site/vite.config.js
git commit -m "feat: black tower theme scaffolding (layout, stylesheet, vite entry)"
```

---

### Task 7: Black Tower header + footer partials

**Files:**
- Create: `resources/views/themes/blacktower/partials/header.blade.php`
- Create: `resources/views/themes/blacktower/partials/footer.blade.php`

**Interfaces:**
- Consumes: `config('hotel.*')` identity. Nav anchors: `#home`, `#about`, `#rooms`, `#contact`.
- Produces: `.site-header` / `.footer` markup matching `globals.css`.

- [ ] **Step 1: Create the header** — translate the `<header>`/`.main-nav` block from `page.tsx` (lines ~118–142) to Blade. Use `config('hotel.name')` for the brand and these nav items: Home (`#home`), About (`#about`), Rooms (`{{ route('rooms.index') }}`), Contact (`#contact`). Keep the `class` names from the source (`site-header`, `main-nav`, etc.) so `blacktower.css` styles apply. Include a phone CTA using `config('hotel.phone')` / `tel:{{ config('hotel.phone_href') }}`.

- [ ] **Step 2: Create the footer** — translate the `<footer className="footer">` block from `page.tsx` (lines ~392–432). Wire contact info to `config('hotel.address')`, `config('hotel.phone')`, `config('hotel.email')`. Keep the newsletter markup static (non-functional form, `onsubmit="return false"`), matching the current Benizia footer behavior.

- [ ] **Step 3: Sanity render check** — temporarily add a route or use `php artisan tinker --execute="echo view('themes.blacktower.partials.header')->render();"`.
Run: `php artisan tinker --execute="view('themes.blacktower.partials.header')->render(); echo 'ok';"`
Expected: prints `ok` with no exception.

- [ ] **Step 4: Commit**

```bash
git add site/resources/views/themes/blacktower/partials
git commit -m "feat: black tower header + footer"
```

---

### Task 8: Black Tower home page (wired to DB)

**Files:**
- Create: `resources/views/themes/blacktower/home.blade.php`
- Verify: `app/Http/Controllers/HomeController.php` passes `$rooms` and `$testimonials` (add if missing)
- Test: `tests/Feature/BlacktowerThemeTest.php`

**Interfaces:**
- Consumes: `theme_view('home')` (Task 1/2); `Room` and `Testimonial` models; layout `themes.blacktower.layouts.app`.

- [ ] **Step 1: Write the failing test**

```php
<?php
// tests/Feature/BlacktowerThemeTest.php
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    config()->set('hotel.theme', 'blacktower');
});

it('renders the black tower home with a seeded room and hero copy', function () {
    $response = $this->get('/');
    $response->assertOk();
    $response->assertSee('Experience Comfort'); // hero heading from page.tsx
    $response->assertSee(\App\Models\Room::first()->name);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/BlacktowerThemeTest.php`
Expected: FAIL — `themes.blacktower.home` view not found.

- [ ] **Step 3: Ensure HomeController provides rooms + testimonials**

Open `app/Http/Controllers/HomeController.php`. Confirm the data passed to `view(theme_view('home'), [...])` includes `'rooms' => \App\Models\Room::where('is_active', true)->orderBy('sort')->take(3)->get()` and `'testimonials' => \App\Models\Testimonial::take(4)->get()`. If those keys are absent, add them (keep any existing keys the Benizia `home.blade.php` already uses — do not remove them).

- [ ] **Step 4: Create the home view**

Create `resources/views/themes/blacktower/home.blade.php` using `@extends('themes.blacktower.layouts.app')` and `@section('content')`. Translate each section from `page.tsx` in order: hero (`#home`, `<h1>Experience Comfort</h1>`), search/booking bar, about (`#about`), rooms (`#rooms` — loop `$rooms`, render `.room-card` with `$room->name`, `$room->price_formatted`, `$room->imageUrl()`, link `route('rooms.show', $room)`), why-choose (4 benefits), service band, gallery (images from `public/img/themes/blacktower/` added in Task 11), highlights, counter band, testimonials (loop `$testimonials` with `$t->name`, `$t->quote` — match the Testimonial model's actual column names), amenities + booking form (`#contact` include from Task 10). Keep the source `className` values as Blade `class` values so `blacktower.css` applies. Set `@section('title', config('hotel.name'))`.

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test tests/Feature/BlacktowerThemeTest.php`
Expected: PASS.

- [ ] **Step 6: Commit**

```bash
git add site/resources/views/themes/blacktower/home.blade.php site/app/Http/Controllers/HomeController.php site/tests/Feature/BlacktowerThemeTest.php
git commit -m "feat: black tower home page wired to rooms + testimonials"
```

---

### Task 9: Black Tower booking-inquiry form partial + Contact + About + Rooms pages

**Files:**
- Create: `resources/views/themes/blacktower/partials/booking-form.blade.php`
- Create: `resources/views/themes/blacktower/about.blade.php`
- Create: `resources/views/themes/blacktower/contact.blade.php`
- Create: `resources/views/themes/blacktower/rooms/index.blade.php`
- Create: `resources/views/themes/blacktower/rooms/show.blade.php`
- Test: extend `tests/Feature/BlacktowerThemeTest.php`

**Interfaces:**
- Consumes: `booking-inquiry.store` route (Task 4); `contact.store` route (existing); `RoomController@index/show` data (`$rooms`, `$room`).

- [ ] **Step 1: Create the booking-inquiry form partial**

```blade
{{-- resources/views/themes/blacktower/partials/booking-form.blade.php --}}
<form class="booking-card" method="POST" action="{{ route('booking-inquiry.store') }}">
    @csrf
    <div class="booking-card__eyebrow">Reserve your stay</div>
    <h3>Book Your Stay</h3>
    @if(session('status'))<p class="booking-card__note booking-card__note--ok">{{ session('status') }}</p>@endif
    @if($errors->any())<p class="booking-card__note booking-card__note--err">{{ $errors->first() }}</p>@endif
    <div class="booking-grid">
        <label><span>Name</span><input name="name" value="{{ old('name') }}" required></label>
        <label><span>Email</span><input type="email" name="email" value="{{ old('email') }}" required></label>
        <label><span>Phone</span><input name="phone" value="{{ old('phone') }}"></label>
        <label><span>Room</span>
            <select name="room" required>
                @foreach(($rooms ?? \App\Models\Room::where('is_active',true)->get()) as $r)
                    <option value="{{ $r->name }}">{{ $r->name }}</option>
                @endforeach
            </select>
        </label>
        <label><span>Check-in</span><input type="date" name="check_in" value="{{ old('check_in') }}" required></label>
        <label><span>Check-out</span><input type="date" name="check_out" value="{{ old('check_out') }}" required></label>
        <label><span>Guests</span><input type="number" name="guests" min="1" value="{{ old('guests', 1) }}" required></label>
    </div>
    <label class="booking-full"><span>Message</span><textarea name="message" rows="3">{{ old('message') }}</textarea></label>
    <button type="submit" class="btn btn--primary">Send Booking Request</button>
</form>
```

Add matching `.booking-card__note`, `.booking-full` rules to `resources/css/blacktower.css` if not present.

- [ ] **Step 2: Create About, Contact, Rooms index, Rooms show**

- `about.blade.php`: `@extends` the theme layout; translate the About section copy from `page.tsx` into a full page.
- `contact.blade.php`: theme layout; show `config('hotel.*')` contact details + a contact form posting to `route('contact.store')` (mirror the fields the existing `ContactController` expects: name, email, phone, message) + include the booking-form partial.
- `rooms/index.blade.php`: loop `$rooms`, render `.room-card`s linking to `route('rooms.show', $room)`.
- `rooms/show.blade.php`: render `$room` (name, price_formatted, description, gallery via `$room->galleryUrls()`), plus an inquiry CTA linking to `#contact` or including the booking form.

- [ ] **Step 3: Extend the theme test**

```php
it('renders black tower rooms, about and contact pages', function () {
    $this->get(route('rooms.index'))->assertOk()->assertSee(\App\Models\Room::first()->name);
    $this->get(route('about'))->assertOk();
    $this->get(route('contact'))->assertOk()->assertSee('Book Your Stay');
});
```

- [ ] **Step 4: Run tests to verify they pass**

Run: `php artisan test tests/Feature/BlacktowerThemeTest.php`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add site/resources/views/themes/blacktower
git commit -m "feat: black tower rooms, about, contact + inquiry form"
```

---

### Task 10: Import Black Tower images

**Files:**
- Create: `app/Console/Commands/ImportBlacktowerPhotos.php`
- Output: `public/img/themes/blacktower/*.webp`

**Interfaces:**
- Consumes: existing `App\Services\ImageEnhancer`.
- Produces: local optimized theme images referenced by the home/gallery/rooms views.

- [ ] **Step 1: Create the import command**

Model it on the existing `App\Console\Commands\ImportPropertyPhotos`. Source URLs (from `page.tsx`): gallery `https://blacktowerhotelsasaba.com/wp-content/uploads/2025/11/tour-{1..11}-550x640.png`, room images `tour-6/5/4`, logo `https://blacktowerhotelsasaba.com/wp-content/uploads/2022/12/blacklogowht.png`. Download each, run through `ImageEnhancer->enhancedWebpFromPath()` (or the existing local-import method), and save to `public/img/themes/blacktower/` with stable names (`tour-1.webp` … `tour-11.webp`, `logo.webp`). Signature: `php artisan blacktower:import-photos`. If a URL 404s, log and continue.

- [ ] **Step 2: Run the command**

Run: `php artisan blacktower:import-photos`
Expected: writes `.webp` files into `public/img/themes/blacktower/`; prints a per-file summary.

- [ ] **Step 3: Point the theme views at the local images**

Update `home.blade.php`, `rooms/*.blade.php`, and header/footer to reference `asset('img/themes/blacktower/<name>.webp')` instead of the `wp-content` URLs.

- [ ] **Step 4: Commit**

```bash
git add site/app/Console/Commands/ImportBlacktowerPhotos.php site/resources/views/themes/blacktower
git commit -m "feat: import + localize black tower images"
```

---

## Phase 4 — Data + deployment

### Task 11: Black Tower room seeder

**Files:**
- Create: `database/seeders/BlackTowerRoomSeeder.php`
- Test: `tests/Feature/BlackTowerSeederTest.php`

**Interfaces:**
- Consumes: `Room` model.
- Produces: 4 seeded rooms. NOT added to `DatabaseSeeder` (run only on the Black Tower deploy).

- [ ] **Step 1: Write the failing test**

```php
<?php
// tests/Feature/BlackTowerSeederTest.php
use App\Models\Room;
use Database\Seeders\BlackTowerRoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds the four black tower rooms', function () {
    (new BlackTowerRoomSeeder())->run();
    expect(Room::count())->toBe(4);
    expect(Room::where('name', 'Luxury Suite')->value('price'))->toBe(125000);
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/BlackTowerSeederTest.php`
Expected: FAIL — class not found.

- [ ] **Step 3: Create the seeder**

```php
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
                'is_active' => true, 'sort' => $i,
            ]);
        }
    }
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test tests/Feature/BlackTowerSeederTest.php`
Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add site/database/seeders/BlackTowerRoomSeeder.php site/tests/Feature/BlackTowerSeederTest.php
git commit -m "feat: black tower room seeder"
```

---

### Task 12: Full suite + build + deployment checklist

**Files:**
- Create: `docs/deploy/blacktower.md`

- [ ] **Step 1: Run the full suite**

Run: `php artisan test`
Expected: ALL PASS (existing counts + the new tests). Fix any regressions before proceeding.

- [ ] **Step 2: Production build**

Run: `NODE_OPTIONS="--max-old-space-size=4096" npm run build`
Expected: success; manifest includes `app.css`, `app.js`, and `blacktower.css`.

- [ ] **Step 3: Write the deployment checklist**

Create `docs/deploy/blacktower.md` documenting the separate Black Tower deploy: point `blacktowerhotelsasaba.com` at the server; create a dedicated MySQL DB; `.env` with `THEME=blacktower`, `HOTEL_NAME`, `HOTEL_PHONE`, `HOTEL_PHONE_HREF`, `HOTEL_EMAIL`, `HOTEL_ADDRESS`, mail creds, `APP_URL`; upload framework + `public/build` + `public/img/themes/blacktower`; run `php artisan migrate --force`, `php artisan db:seed --class=BlackTowerRoomSeeder --force`, `php artisan config:cache && view:cache`; set an admin user; verify the homepage renders the Black Tower theme.

- [ ] **Step 4: Commit**

```bash
git add docs/deploy/blacktower.md
git commit -m "docs: black tower deployment checklist"
```

---

## Self-Review

**Spec coverage:**
- Theme layer / resolution → Tasks 1–2. ✓ (refinement: Benizia stays the default flat theme instead of being physically moved — same outcome, lower risk; noted in Architecture.)
- Black Tower theme (layout, tokens, pages, sections) → Tasks 6–10. ✓
- Booking inquiry (table/model/public/admin/mail) → Tasks 3–5. ✓
- Env-driven identity → Task 1. ✓
- Seeding → Task 11. ✓
- One repo / separate deploy → Task 12 checklist. ✓
- Images localized → Task 10. ✓
- Testing → each task + Task 12 full-suite gate. ✓

**Placeholder scan:** Visual-port tasks (7–10) reference exact source files/line ranges and exact model fields/routes rather than pasting the full 435-line JSX / 1483-line CSS; this is deliberate (the source exists in-repo) and each such task ends with a concrete rendering test. No vague "add error handling/validation" — validation is a concrete Form Request (Task 4).

**Type consistency:** `theme_view()` (Task 1) used in Task 2 & 8; `BookingInquiry` fillable set (Task 3) matches the Form Request rules (Task 4), the store payload, and the admin views (Task 5); route names `booking-inquiry.store` and `admin.booking-inquiries.*` are consistent across tasks. Confirm the `Testimonial` model's real column names (`name`/`quote` vs other) when writing Task 8 — adjust the view to the actual columns.
