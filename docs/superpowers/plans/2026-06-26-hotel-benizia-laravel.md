# Hotel Benizia Laravel SEO Redesign — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a production-grade, SEO-ready Laravel 11 website for Hotel Benizia (luxury hotel + serviced apartments, Asaba) by merging the two supplied React designs into a server-rendered Blade app with a guest booking flow and an admin panel.

**Architecture:** Laravel 11 MVC. Blade SSR + Tailwind + Alpine.js for the public site and admin. SQLite database, Eloquent models seeded from the merged design data. Reusable Blade components for cards/sections and an `<x-seo>` + JSON-LD layer. Breeze for admin auth. Booking uses a commitment-fee model with a stubbed Paystack controller.

**Tech Stack:** PHP 8.3, Laravel 11, Blade, Tailwind CSS 3, Alpine.js, Vite, SQLite, Laravel Breeze (Blade), Pest/PHPUnit feature tests, Chart.js (admin mini-chart).

## Global Constraints

- Laravel 11, PHP 8.3, SQLite (`DB_CONNECTION=sqlite`).
- App name "Hotel Benizia"; tagline "Luxury in the heart of Asaba".
- Palette: deep green `#1D5C52`, gold `#C9A96E`, cream/charcoal neutrals. Headings serif (Playfair Display), body Inter.
- Contact (verbatim, live-site source of truth): phone `+234 813 406 2487` (`tel:+2348134062487`), email `booking@hotelbenizia.ng`, address `1 Kingsley Emu Street, Central Area, Asaba 320242, Delta State`.
- Currency NGN, prices integer naira, displayed as `₦30,000`.
- Booking policy: commitment fee **40%**, cancellation window **24h**, balance due at check-in.
- Canonical host `https://hotelbenizia.ng` (via `APP_URL`).
- Every public page: one `<h1>`, `<x-seo>` meta, semantic landmarks, image alt text, mobile-first.
- All inventory/content seeded so `php artisan migrate:fresh --seed` produces a complete site.
- Run all artisan/git commands from project root `app/` (the Laravel app created in Task 1). Frequent commits per task.

---

## Milestone A — Foundation

### Task 1: Scaffold Laravel app, SQLite, Tailwind/Alpine, brand config

**Files:**
- Create: `app/` (Laravel project, created into subdir to keep design folders separate)
- Modify: `app/.env`, `app/tailwind.config.js`, `app/resources/css/app.css`, `app/resources/js/app.js`, `app/vite.config.js`
- Create: `app/config/hotel.php`
- Test: `app/tests/Feature/SmokeTest.php`

**Interfaces:**
- Produces: `config('hotel.*')` global content (name, tagline, contact, socials, booking policy, nav links, amenity ticker, facilities, menu).

- [ ] **Step 1: Create the Laravel project into `app/`**

Run (from `c:\Users\DELL\hotel`):
```bash
composer create-project laravel/laravel app "11.*" --no-interaction
```

- [ ] **Step 2: Configure SQLite**

Create `app/database/database.sqlite` (empty file). In `app/.env` set:
```
APP_NAME="Hotel Benizia"
APP_URL=https://hotelbenizia.ng
DB_CONNECTION=sqlite
```
Remove the other `DB_*` lines. Run:
```bash
cd app && php artisan migrate
```
Expected: migration table + default tables created, no errors.

- [ ] **Step 3: Install Tailwind + Alpine via Vite**

Run in `app/`:
```bash
npm install -D tailwindcss@^3 postcss autoprefixer
npx tailwindcss init -p
npm install alpinejs
```
Set `tailwind.config.js` `content` to `['./resources/**/*.blade.php','./resources/**/*.js']` and extend theme colors:
```js
colors: { benizia: { green: '#1D5C52', gold: '#C9A96E', cream: '#FAF7F1', charcoal: '#1F2421' } },
fontFamily: { serif: ['"Playfair Display"','serif'], sans: ['Inter','sans-serif'] },
```
`resources/css/app.css`:
```css
@tailwind base; @tailwind components; @tailwind utilities;
```
`resources/js/app.js`:
```js
import Alpine from 'alpinejs';
window.Alpine = Alpine; Alpine.start();
```

- [ ] **Step 4: Create `config/hotel.php`**

```php
<?php
return [
  'name' => 'Hotel Benizia',
  'tagline' => 'Luxury in the heart of Asaba',
  'phone' => '+234 813 406 2487',
  'phone_href' => '+2348134062487',
  'email' => 'booking@hotelbenizia.ng',
  'address' => '1 Kingsley Emu Street, Central Area, Asaba 320242, Delta State',
  'canonical' => 'https://hotelbenizia.ng',
  'socials' => [
    'facebook' => '#','twitter' => '#','instagram' => '#','youtube' => '#','pinterest' => '#',
  ],
  'nav' => [
    ['label'=>'Home','route'=>'home'],
    ['label'=>'Rooms','route'=>'rooms.index'],
    ['label'=>'HB Apartments','route'=>'apartments.index'],
    ['label'=>'Restaurant','route'=>'restaurant'],
    ['label'=>'Gallery','route'=>'gallery'],
    ['label'=>'Blog','route'=>'blog.index'],
    ['label'=>'About','route'=>'about'],
    ['label'=>'Contact','route'=>'contact'],
  ],
  'ticker' => ['Breakfast Included','Swimming Pool','High Speed WiFi','Spa & Wellness','Pick Up & Drop','Fitness Hub','24/7 Restaurant','Live Entertainment','Event Halls','Free Parking','Room Service','24/7 Security'],
  'booking' => ['commitment_percent'=>40,'cancellation_hours'=>24,'balance_note'=>'Pay the remaining balance at check-in after reservation confirmation.'],
];
```

- [ ] **Step 5: Write smoke test**

`tests/Feature/SmokeTest.php`:
```php
<?php
use function Pest\Laravel\get;
it('loads the welcome route', function () { get('/')->assertStatus(200); });
it('exposes hotel config', function () { expect(config('hotel.name'))->toBe('Hotel Benizia'); });
```

- [ ] **Step 6: Run tests**

Run: `php artisan test --filter=SmokeTest`
Expected: PASS (welcome page still default at this point).

- [ ] **Step 7: Commit**

```bash
git add app && git commit -m "feat: scaffold Laravel app with SQLite, Tailwind, Alpine, hotel config"
```

---

### Task 2: Database schema — migrations

**Files:**
- Create: migrations for `rooms`, `apartments`, `payment_methods`, `bookings`, `blog_posts`, `testimonials`, `faqs`, `messages`; modify `users` migration (add `is_admin`).
- Test: `tests/Feature/SchemaTest.php`

**Interfaces:**
- Produces: tables per spec §5. `bookings` has morphable `bookable_type`/`bookable_id`.

- [ ] **Step 1: Write failing schema test**

`tests/Feature/SchemaTest.php`:
```php
<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);
it('has core tables with key columns', function () {
  expect(Schema::hasColumns('rooms', ['slug','name','category','price','amenities','gallery']))->toBeTrue();
  expect(Schema::hasColumns('apartments', ['slug','status','amenities']))->toBeTrue();
  expect(Schema::hasColumns('bookings', ['ref','bookable_type','bookable_id','commitment_fee','status']))->toBeTrue();
  expect(Schema::hasColumns('payment_methods', ['name','type','accepts_commitment_fee']))->toBeTrue();
  expect(Schema::hasColumn('users','is_admin'))->toBeTrue();
});
```

- [ ] **Step 2: Run to verify fail**

Run: `php artisan test --filter=SchemaTest` → FAIL (tables missing).

- [ ] **Step 3: Create migrations**

`php artisan make:migration create_rooms_table` etc. Key columns (json columns via `$table->json(...)`):
- rooms: `id, slug unique, name, category, integer price, price_label, size, guests, beds, baths nullable, sqm nullable, rating, integer reviews default 0, text excerpt, longText description, image, json gallery, json amenities, json includes, json policies, json best_for nullable, boolean is_active default true, integer sort default 0, timestamps`
- apartments: `id, slug unique, name, type, integer price, price_label, enum-as-string status default 'Available', image, json gallery nullable, bedrooms, bathrooms, occupancy, text description, json amenities, boolean is_active default true, integer sort default 0, timestamps`
- payment_methods: `id, name, type, provider, account_name nullable, account_number nullable, bank_name nullable, text instructions, boolean active default true, boolean accepts_commitment_fee default true, integer sort default 0, timestamps`
- bookings: `id, ref unique, string bookable_type, unsignedBigInteger bookable_id, guest_name, guest_email, guest_phone, date check_in, date check_out, integer nights, integer guests, integer total, integer commitment_percent, integer commitment_fee, integer balance_due, string status default 'Pending Payment', foreignId payment_method_id nullable, string proof_path nullable, text notes nullable, timestamps; index [bookable_type,bookable_id]`
- blog_posts: `id, slug unique, title, text excerpt, longText body, category, category_color nullable, image, author, timestamp published_at, timestamps`
- testimonials: `id, name, role, integer rating default 5, text text, avatar nullable, timestamps`
- faqs: `id, question, text answer, integer sort default 0, timestamps`
- messages: `id, name, email, phone nullable, subject nullable, text message, timestamp read_at nullable, timestamps`
- users: add `$table->boolean('is_admin')->default(false);`

- [ ] **Step 4: Migrate fresh + run test**

Run: `php artisan migrate:fresh && php artisan test --filter=SchemaTest` → PASS.

- [ ] **Step 5: Commit**

```bash
git add database/migrations app/.. && git commit -m "feat: add database schema migrations"
```

---

### Task 3: Eloquent models + casts + relationships

**Files:**
- Create: `app/Models/Room.php`, `Apartment.php`, `PaymentMethod.php`, `Booking.php`, `BlogPost.php`, `Testimonial.php`, `Faq.php`, `Message.php`; modify `User.php`.
- Test: `tests/Feature/ModelTest.php`

**Interfaces:**
- Produces:
  - `Room` / `Apartment` / `BlogPost`: `getRouteKeyName(): 'slug'`; json casts for array columns.
  - `Booking::bookable(): MorphTo`; `Booking::paymentMethod(): BelongsTo`.
  - `Room::priceFormatted(): string` accessor returning `₦30,000`.
  - `User::isAdmin(): bool`.

- [ ] **Step 1: Write failing model test**

```php
<?php
use App\Models\{Room,Booking};
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);
it('casts room json and formats price', function () {
  $r = Room::create([... minimal valid attrs with amenities=>['WiFi'], price=>30000 ...]);
  expect($r->amenities)->toBeArray()->toContain('WiFi');
  expect($r->price_formatted)->toBe('₦30,000');
  expect($r->getRouteKeyName())->toBe('slug');
});
```
(Use a factory or inline array with all non-nullable columns.)

- [ ] **Step 2: Run → FAIL.**

Run: `php artisan test --filter=ModelTest`

- [ ] **Step 3: Implement models**

Each model: `protected $guarded = [];`, `protected $casts` for json/date columns. `Room`/`Apartment`/`BlogPost` set `getRouteKeyName() { return 'slug'; }`. `Room` accessor:
```php
protected function priceFormatted(): Attribute {
  return Attribute::get(fn () => '₦'.number_format($this->price));
}
```
`Booking`: `bookable()` morphTo, `paymentMethod()` belongsTo. `User`: add `is_admin` to casts (`bool`) and `isAdmin()`.

- [ ] **Step 4: Run → PASS. Step 5: Commit** `feat: add Eloquent models with casts and relationships`.

---

### Task 4: Seeders from merged design data

**Files:**
- Create: `database/seeders/{RoomSeeder,ApartmentSeeder,PaymentMethodSeeder,BlogSeeder,TestimonialSeeder,FaqSeeder,AdminUserSeeder}.php`; modify `DatabaseSeeder.php`.
- Test: `tests/Feature/SeederTest.php`

**Interfaces:**
- Consumes: source data from `laravel-hotel-seo-redesign (1)/src/data/hotel.ts` (rooms, apartments, payment methods, faqs, testimonials, blog posts, menu) and `laravel-hotel-seo-redesign/src/data/index.ts` (room amenities/policies/includes depth).
- Produces: seeded DB — 5 rooms, 3 apartments, ≥3 payment methods, ≥3 blog posts, ≥5 testimonials, ≥5 faqs, 1 admin user `admin@hotelbenizia.ng` / `password`.

- [ ] **Step 1: Write failing seeder test**

```php
it('seeds inventory and admin', function () {
  $this->seed();
  expect(\App\Models\Room::count())->toBe(5);
  expect(\App\Models\Apartment::count())->toBe(3);
  expect(\App\Models\User::where('is_admin',true)->count())->toBe(1);
});
```
(uses RefreshDatabase)

- [ ] **Step 2: Run → FAIL.**

- [ ] **Step 3: Implement seeders** using the exact data arrays read from the two `data` files (names, slugs, prices, amenities, gallery URLs, descriptions). Merge: use variant `(1)` room set/prices (Deluxe Classic, Deluxe Premium, Alcove Room, Diplomatic Suite, Penthouse Suite) enriched with the first variant's `policies`/`includes`. Hash admin password with `Hash::make('password')`, `is_admin=>true`. Register all in `DatabaseSeeder`.

- [ ] **Step 4: Run `php artisan migrate:fresh --seed` then test → PASS. Step 5: Commit** `feat: seed rooms, apartments, payments, blog, testimonials, faqs, admin`.

---

## Milestone B — Public Site Shell & SEO Core

### Task 5: SEO component + JSON-LD partials

**Files:**
- Create: `resources/views/components/seo.blade.php`, `resources/views/components/schema/hotel.blade.php`, `.../schema/breadcrumb.blade.php`, `.../schema/product.blade.php`, `.../schema/article.blade.php`, `.../schema/faq.blade.php`.
- Test: `tests/Feature/SeoComponentTest.php`

**Interfaces:**
- Produces: `<x-seo :title :description :image :canonical :type />` rendering `<title>`, meta description, canonical, OG + Twitter tags with config defaults. `<x-schema.hotel />` etc. emit `<script type="application/ld+json">`.

- [ ] **Step 1: Failing test** — render a tiny view using `<x-seo title="Rooms" description="d" />` and assert response contains `<title>Rooms`, `og:title`, `twitter:card`, canonical. Assert `<x-schema.hotel />` output contains `"@type":"Hotel"` and the address locality `Asaba`.

- [ ] **Step 2: Run → FAIL. Step 3: Implement components** (props with defaults from `config('hotel')`, JSON via `@json`). Build a temporary test route/view or use Blade::render in the test.

- [ ] **Step 4: Run → PASS. Step 5: Commit** `feat: add SEO meta component and JSON-LD schema partials`.

---

### Task 6: Public layout — header, footer, ticker, base Blade components

**Files:**
- Create: `resources/views/layouts/app.blade.php`, `resources/views/partials/{header,footer,amenity-ticker}.blade.php`, components `resources/views/components/{page-hero,section-intro,cta,rating-stars,reveal,room-card,apartment-card,testimonial,amenity-ribbon}.blade.php`.
- Test: `tests/Feature/LayoutTest.php`

**Interfaces:**
- Consumes: `config('hotel.nav')`, `<x-seo>`.
- Produces: `layouts.app` with `$slot`, `$title`/`$description` page props passed to `<x-seo>`, `<x-schema.hotel>` in `<head>`; named components above (e.g. `<x-room-card :room="$room" />`).

- [ ] **Step 1: Failing test** — create a throwaway route rendering `layouts.app`; assert 200, contains hotel name, phone `+234 813 406 2487`, nav labels (Home, HB Apartments), footer email `booking@hotelbenizia.ng`.

- [ ] **Step 2: Run → FAIL. Step 3: Implement** layout (responsive header w/ Alpine mobile menu + sticky "Book Now" CTA, amenity ticker marquee, rich footer w/ socials/contact/quick links, `<x-schema.hotel>`). Implement card/section components using Tailwind brand colors. `<x-reveal>` = Alpine `x-intersect` fade-up.

- [ ] **Step 4: Run → PASS. Step 5: Commit** `feat: add public layout, header/footer, reusable Blade components`.

---

## Milestone C — Public Pages

### Task 7: Routes + controllers skeleton

**Files:**
- Modify: `routes/web.php`. Create: `app/Http/Controllers/{Home,Room,Apartment,Restaurant,Gallery,Event,Blog,Page,Contact}Controller.php`.
- Test: `tests/Feature/PublicRoutesTest.php`

**Interfaces:**
- Produces named routes: `home, rooms.index, rooms.show, apartments.index, apartments.show, restaurant, gallery, events, blog.index, blog.show, about, contact, faq`. Controllers return views with seeded data.

- [ ] **Step 1: Failing test** — `migrate:fresh --seed` (RefreshDatabase + seed), GET each route → 200; `rooms.show` with a seeded slug → 200 and contains room name.

- [ ] **Step 2: Run → FAIL. Step 3: Implement** routes + thin controllers fetching models (`Room::where('is_active',true)->orderBy('sort')->get()`, `findOrFail` via slug binding). Create minimal Blade views per page (real content sections wired in Tasks 8–9, but views must exist and render seeded data + `<x-seo>`).

- [ ] **Step 4: Run → PASS. Step 5: Commit** `feat: add public routes and controllers`.

---

### Task 8: Home page (merged hero, rooms, facilities, dining, testimonials, blog, CTA)

**Files:**
- Modify: `resources/views/home.blade.php`, `HomeController`.
- Test: `tests/Feature/HomePageTest.php`

**Interfaces:**
- Consumes: rooms (featured 5), testimonials, blog posts (3), `config('hotel')`.

- [ ] **Step 1: Failing test** — GET `/` contains: tagline, a booking search bar (check-in/out inputs), at least 3 room names, "Restaurant", a testimonial guest name, single `<h1>`, and JSON-LD `"@type":"Hotel"`.

- [ ] **Step 2: Run → FAIL. Step 3: Implement** home sections from both designs: hero with overlay + inline availability form (posts to `booking.create`), intro/about strip, room showcase grid (`x-room-card`), amenity ribbon, facilities grid, dining/bar feature, testimonials carousel (Alpine), latest blog, location/CTA. Use Benizia images from data files. Per-page `<x-seo>` with local-SEO title/description.

- [ ] **Step 4: Run → PASS. Step 5: Commit** `feat: build home page`.

---

### Task 9: Remaining public pages (rooms, apartments, restaurant, gallery, events, blog, about, contact, faq)

> Right-sized as one task per page-group could be split; here each page is a sub-step with its own assertion. Reviewer may reject any page independently.

**Files:**
- Modify the Blade views created in Task 7 + relevant controllers.
- Test: `tests/Feature/PagesContentTest.php`

**Interfaces:**
- Consumes: seeded models. Produces: `rooms.show`/`apartments.show` include `<x-schema.product>` + `<x-schema.breadcrumb>`; `faq` includes `<x-schema.faq>`; `blog.show` includes `<x-schema.article>`.

- [ ] **Step 1: Failing test** — for each page assert 200 + signature content: rooms index lists 5 rooms w/ prices; room detail shows amenities, includes, policies, gallery, "Book" button, Product schema; apartments index lists 3 + status badges; restaurant shows menu items + prices; gallery shows images w/ alt; events shows halls/boardroom copy; blog index lists posts; blog detail shows body + Article schema; about shows vision/mission; contact shows form + map/address; faq shows questions + FAQPage schema.

- [ ] **Step 2: Run → FAIL. Step 3: Implement** each view with merged design sections, `x-page-hero`, breadcrumbs, brand styling, alt text, per-page `<x-seo>` and relevant schema.

- [ ] **Step 4: Run → PASS. Step 5: Commit** `feat: build all public content pages with schema`.

---

## Milestone D — Booking Flow

### Task 10: Booking form + price/commitment computation

**Files:**
- Create: `app/Http/Controllers/BookingController.php`, `app/Services/BookingCalculator.php`, views `resources/views/booking/create.blade.php`.
- Modify: `routes/web.php`.
- Test: `tests/Feature/BookingCalculatorTest.php`, `tests/Feature/BookingFormTest.php`

**Interfaces:**
- Produces: `BookingCalculator::quote(int $price, Carbon $in, Carbon $out, int $commitmentPercent): array` → `['nights','total','commitment_fee','balance_due']`. Routes `booking.create` (GET `/book/{type}/{slug}`), `booking.store` (POST `/book`).

- [ ] **Step 1: Failing unit test** for `BookingCalculator::quote(30000, 2026-03-01, 2026-03-04, 40)` → nights 3, total 90000, commitment_fee 36000, balance_due 54000.

- [ ] **Step 2: Run → FAIL. Step 3: Implement** calculator (nights = diffInDays, clamp ≥1). Build booking form view (room/apartment summary, date pickers, guests, contact fields) reading bookable by type+slug.

- [ ] **Step 4: Form test** — GET `/book/room/{slug}` → 200 shows price + commitment note. Run → PASS. Step 5: Commit `feat: add booking form and commitment-fee calculator`.

---

### Task 11: Checkout, persistence, payment methods, success

**Files:**
- Create: `app/Http/Controllers/CheckoutController.php`, `app/Http/Requests/StoreBookingRequest.php`, views `resources/views/checkout/{index,success}.blade.php`.
- Test: `tests/Feature/CheckoutTest.php`

**Interfaces:**
- Consumes: `BookingCalculator`, `PaymentMethod` (active), booking policy.
- Produces: `booking.store` validates + persists `Booking` (ref `HB-#####`, status `Pending Payment`), redirects to `booking.success` (`/booking/success/{ref}`). Proof-of-payment upload stored to `storage/app/public/proofs`.

- [ ] **Step 1: Failing test** — POST valid booking payload → DB has booking with computed commitment_fee + generated ref; redirect to success; success page shows ref + bank transfer details. Invalid (check_out ≤ check_in) → 422/redirect with errors, no DB row.

- [ ] **Step 2: Run → FAIL. Step 3: Implement** `StoreBookingRequest` rules; controller computes quote, generates unique ref, creates booking, handles optional proof upload; checkout view lists active payment methods (bank transfer details + Paystack option); success view shows summary, commitment fee, balance, next steps.

- [ ] **Step 4: Run → PASS. Step 5: Commit** `feat: add checkout, booking persistence, payment methods, success page`.

---

### Task 12: Paystack controller stub

**Files:**
- Create: `app/Http/Controllers/PaystackController.php`; modify `routes/web.php`, `config/services.php`, `.env.example`.
- Test: `tests/Feature/PaystackStubTest.php`

**Interfaces:**
- Produces: routes `paystack.init` (POST), `paystack.callback` (GET). Without keys, `init` flashes "Paystack not configured" and returns to checkout. Methods contain clearly marked `// TODO: add live PAYSTACK_SECRET_KEY` integration points.

- [ ] **Step 1: Failing test** — POST `paystack.init` for a booking with no keys set → redirect back with `status` message; booking remains `Pending Payment`.

- [ ] **Step 2: Run → FAIL. Step 3: Implement** stub reading `config('services.paystack.secret')`; if empty, short-circuit with message; else scaffold (commented) Http call to `https://api.paystack.co/transaction/initialize`. Add `services.paystack` config + env placeholders.

- [ ] **Step 4: Run → PASS. Step 5: Commit** `feat: add integration-ready Paystack controller stub`.

---

### Task 13: Contact form submission

**Files:**
- Modify: `ContactController`, `resources/views/contact.blade.php`; create `app/Http/Requests/StoreMessageRequest.php`.
- Test: `tests/Feature/ContactFormTest.php`

**Interfaces:**
- Produces: `contact.store` (POST) validates + persists `Message`, redirects back with success flash.

- [ ] **Step 1: Failing test** — POST valid contact payload → `messages` row created + redirect with flash; missing email → validation error, no row.
- [ ] **Step 2: Run → FAIL. Step 3: Implement** request + controller + wire form. **Step 4: PASS. Step 5: Commit** `feat: add contact form submission`.

---

## Milestone E — Admin Panel

### Task 14: Breeze auth + admin middleware + layout + dashboard

**Files:**
- Run Breeze install; create `app/Http/Middleware/EnsureAdmin.php`, `resources/views/layouts/admin.blade.php`, `app/Http/Controllers/Admin/DashboardController.php`, `resources/views/admin/dashboard.blade.php`; modify `routes/web.php`, `bootstrap/app.php` (register middleware alias).
- Test: `tests/Feature/AdminAccessTest.php`

**Interfaces:**
- Produces: `admin` middleware (redirects non-admins), `/admin` dashboard with stat cards (revenue from bookings, occupancy, open bookings, available units) + recent bookings + Chart.js mini-chart.

- [ ] **Step 1: Install Breeze**

```bash
composer require laravel/breeze --dev && php artisan breeze:install blade --no-interaction && npm install && npm run build
```

- [ ] **Step 2: Failing test** — guest GET `/admin` → redirect to login; non-admin user → 403; admin user → 200 sees "Dashboard" + a stat label. Run → FAIL.

- [ ] **Step 3: Implement** `EnsureAdmin` (checks `auth()->user()?->isAdmin()`), register alias `admin` in `bootstrap/app.php`, admin layout (sidebar nav), dashboard controller computing stats from seeded bookings. Add Chart.js via CDN or npm in admin layout.

- [ ] **Step 4: Run → PASS. Step 5: Commit** `feat: add admin auth, middleware, layout, dashboard`.

---

### Task 15: Admin CRUD — rooms, apartments, payment methods

**Files:**
- Create: `Admin/{RoomController,ApartmentController,PaymentMethodController}.php`, resource views under `resources/views/admin/{rooms,apartments,payment-methods}/`.
- Modify: `routes/web.php` (admin group).
- Test: `tests/Feature/AdminCrudTest.php`

**Interfaces:**
- Produces: resourceful routes `admin.rooms.*` etc. (index/create/store/edit/update/destroy) behind `auth`+`admin`. Form requests validate; json array fields entered as newline/comma lists.

- [ ] **Step 1: Failing test** (acting as admin) — create a room via POST → DB row + redirect; update changes price; index lists it; delete removes it. Repeat minimal create for apartment + payment method.

- [ ] **Step 2: Run → FAIL. Step 3: Implement** controllers + views (tables with edit/delete, forms with validation, active toggles, image URL fields). Parse list inputs to arrays.

- [ ] **Step 4: Run → PASS. Step 5: Commit** `feat: add admin CRUD for rooms, apartments, payment methods`.

---

### Task 16: Admin — bookings, blog, testimonials, faqs, messages

**Files:**
- Create: `Admin/{BookingController,BlogController,TestimonialController,FaqController,MessageController}.php` + views.
- Test: extend `tests/Feature/AdminCrudTest.php`

**Interfaces:**
- Produces: `admin.bookings.index/show/update` (status update + proof view), `admin.blog.*` full CRUD, `admin.testimonials.*`, `admin.faqs.*`, `admin.messages.index/show/destroy` (mark read).

- [ ] **Step 1: Failing test** — admin updates a booking status → persisted; creates a blog post → row + appears on public `blog.index`; marks a message read → `read_at` set.
- [ ] **Step 2: Run → FAIL. Step 3: Implement** controllers + views. **Step 4: PASS. Step 5: Commit** `feat: add admin management for bookings, blog, testimonials, faqs, messages`.

---

## Milestone F — SEO Endpoints, Polish, Docs

### Task 17: sitemap.xml + robots.txt

**Files:**
- Create: `app/Http/Controllers/SitemapController.php`, `resources/views/sitemap.blade.php`, `public/robots.txt` (or route).
- Modify: `routes/web.php`.
- Test: `tests/Feature/SitemapTest.php`

**Interfaces:**
- Produces: GET `/sitemap.xml` → valid XML listing home + static pages + every active room/apartment/blog URL with `lastmod`. `/robots.txt` allows all + references sitemap.

- [ ] **Step 1: Failing test** — GET `/sitemap.xml` → 200, content-type xml, contains a seeded room slug URL + canonical host; GET `/robots.txt` → contains `Sitemap: https://hotelbenizia.ng/sitemap.xml`.
- [ ] **Step 2: Run → FAIL. Step 3: Implement** controller building `<urlset>` from models; robots route/file. **Step 4: PASS. Step 5: Commit** `feat: add sitemap.xml and robots.txt`.

---

### Task 18: SEO/perf polish pass

**Files:**
- Modify: `layouts/app.blade.php` (preconnect fonts, meta theme-color, favicon), image tags across views (loading=lazy, width/height, alt), `<x-schema.breadcrumb>` on inner pages, 404 view.
- Test: `tests/Feature/SeoPolishTest.php`

**Interfaces:**
- Produces: every public page has exactly one `<h1>`, a meta description, canonical, OG image; images lazy + alt.

- [ ] **Step 1: Failing test** — crawl key routes asserting exactly one `<h1>`, presence of `name="description"`, `rel="canonical"`, and that `<img` tags include `alt=` (sample check). 404 returns branded page.
- [ ] **Step 2: Run → FAIL. Step 3: Fix** offending views; add lazy loading, dimensions, alt; preconnect Google Fonts; theme-color; custom 404.
- [ ] **Step 4: Run → PASS. Step 5: Commit** `feat: SEO and performance polish pass`.

---

### Task 19: Full suite, build, README

**Files:**
- Create: `README.md`. Modify: `.env.example`.
- Test: full `php artisan test`.

- [ ] **Step 1:** Run `php artisan migrate:fresh --seed && php artisan test` → all green. Run `npm run build` → succeeds.
- [ ] **Step 2:** Write `README.md`: requirements, `composer install`, `npm install`, `.env` setup, `migrate:fresh --seed`, `npm run dev`, `php artisan serve`, admin credentials (`admin@hotelbenizia.ng` / `password`), where to add Paystack keys, how to switch to MySQL for production, SEO notes (sitemap/robots/schema).
- [ ] **Step 3: Commit** `docs: add README and finalize build`.
- [ ] **Step 4:** Manual responsive smoke check of home, rooms, room detail, booking, admin on mobile widths (note results).

---

## Self-Review

**Spec coverage:** §1 stack→T1; §3 brand→T1/T6; §4 site map→T7–T9 + booking T10–T11 + admin T14–T16 + SEO endpoints T17; §5 data model→T2–T4; §6 booking logic→T10–T12; §7 admin→T14–T16; §8 SEO layer→T5,T8/T9 schema,T17,T18; §9 component architecture→T5/T6; §10 quality→tests throughout + T19; §11 out-of-scope respected (Paystack stub only, guest checkout, no i18n). All covered.

**Placeholder scan:** No "TBD/TODO-as-deliverable"; the only intentional `// TODO` is the documented Paystack live-key integration point (a stub per spec), not a plan gap.

**Type consistency:** `BookingCalculator::quote()` keys (`nights,total,commitment_fee,balance_due`) used consistently in T10–T11; `priceFormatted`/`price_formatted` accessor consistent; route names match across controllers, layout nav, and tests; `EnsureAdmin`/`admin` alias consistent T14–T16.
