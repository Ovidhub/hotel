# Hotel Benizia — Official Website

A full-featured hotel booking and marketing website for **Hotel Benizia**, a luxury hotel and serviced apartments located at 1 Kingsley Emu Street, Summit Road, Asaba, Delta State, Nigeria.

Built with **Laravel 11**, **Blade**, **Tailwind CSS v4**, **Alpine.js**, and **SQLite** (swappable to MySQL for production). SEO-optimized with structured data, sitemap, and per-page meta.

---

## Requirements

| Dependency | Minimum version |
|---|---|
| PHP | 8.3 |
| Composer | 2.x |
| Node.js | 20+ |
| npm | 10+ |
| SQLite | 3.x (bundled with PHP on most platforms) |

---

## Local Setup

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy environment file
cp .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Create the SQLite database file (if it does not exist)
touch database/database.sqlite
# On Windows: type NUL > database\database.sqlite

# 5. Run migrations and seed demo data
php artisan migrate:fresh --seed

# 6. Install JS dependencies and build assets
npm install
npm run build          # production build
# -- or --
npm run dev            # Vite dev server with HMR

# 7. Link storage for file uploads (proof-of-payment images)
php artisan storage:link

# 8. Start the development server
php artisan serve
```

Visit `http://localhost:8000` to see the site.

---

## Admin Panel

| Item | Value |
|---|---|
| URL | `http://localhost:8000/admin` |
| Login page | `http://localhost:8000/login` |
| Email | `admin@hotelbenizia.ng` |
| Password | `password` |

> **Important:** Change the admin password before deploying to production.
> ```bash
> php artisan tinker
> >>> User::first()->update(['password' => bcrypt('your-secure-password')]);
> ```

The admin panel covers:

- Room and apartment CRUD (images, amenities, pricing, availability toggle)
- Booking management (status updates: Pending Payment -> Confirmed -> Checked In -> Cancelled)
- Blog post management (rich descriptions, SEO slugs)
- Contact message inbox (read/unread tracking, delete)
- FAQ, testimonial, and gallery management

---

## Booking Flow

Hotel Benizia uses a **commitment-fee model**:

1. Guest selects a room or apartment and desired check-in / check-out dates.
2. System calculates: **40% commitment fee** due now, 60% balance payable on arrival.
3. Guest chooses a payment method:
   - **Bank Transfer** — bank account details are shown; guest uploads proof of payment.
   - **Paystack** — online card payment (see the Paystack section below).
4. Admin reviews the uploaded proof and manually confirms the booking.
5. Guest receives a booking reference and summary on-screen.

---

## Availability & Booking.com (iCal) Sync

Each room and apartment has a date-level availability calendar. A date is
unavailable when active bookings reach the room's **Units available** count,
or when an admin/OTA **block** covers it. The booking flow rejects unavailable
date ranges.

Manage it under **Admin → Availability**:

- **Block / unblock dates** per room or apartment (e.g. maintenance).
- **Export to Booking.com** — copy the room's public `.ics` link and paste it
  into Booking.com's *Calendar → Sync calendars → Connect a calendar*. It
  publishes that room's booked & blocked dates.
- **Import from Booking.com** — paste Booking.com's exported `.ics` link into
  the import box. Booking.com reservations then auto-block those dates here.

Imports refresh hourly via the scheduler (or use **Sync now**). For the hourly
import to run, the Laravel scheduler must be active:

```bash
# Add to crontab (Linux) — runs the scheduler every minute:
* * * * * cd /path/to/site && php artisan schedule:run >> /dev/null 2>&1

# Or run a one-off import manually:
php artisan calendar:sync
```

> Notes: iCal sync is **date-level** (no rates) and refreshes periodically,
> not instantly. Booking.com offers iCal calendar sync primarily for
> **apartments/homes** (HB Apartments); standard hotel rooms typically require
> a channel manager. See the original options discussion for details.

---

## Paystack Integration

The site ships with a **Paystack stub** that falls back gracefully to the bank-transfer path when no live keys are configured. To enable live card payments:

1. Create an account at [paystack.com](https://paystack.com) and obtain your API keys.
2. Add the following to `.env`:

```dotenv
PAYSTACK_PUBLIC_KEY=pk_live_your_public_key_here
PAYSTACK_SECRET_KEY=sk_live_your_secret_key_here
PAYSTACK_BASE_URL=https://api.paystack.co
PAYSTACK_CURRENCY=NGN
```

**Without live keys:** The booking flow continues to work — guests are directed to the bank-transfer path automatically. No errors are shown.

---

## SEO Features

- **Per-page meta tags** — unique `<title>`, `<meta name="description">`, Open Graph (`og:*`), and Twitter Card tags on every page.
- **JSON-LD structured data** — `Hotel`, `LodgingBusiness`, `Product` (rooms/apartments), `Article` (blog posts), `FAQPage`, and `BreadcrumbList` schemas injected on the appropriate pages.
- **`/sitemap.xml`** — auto-generated XML sitemap covering all public pages, active rooms, active apartments, and published blog posts. Includes `<lastmod>`, `<changefreq>`, and `<priority>` elements.
- **`/robots.txt`** — disallows admin, checkout, and auth paths; references the sitemap URL.

---

## Switching to MySQL (Production)

The default database is SQLite, which works well for development and small deployments. For high-traffic production use, switch to MySQL:

1. Create a MySQL database and user.
2. Update `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel_benizia
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

3. Run migrations:

```bash
php artisan migrate --seed
```

No application code changes are required — Laravel's database abstraction layer handles the rest.

---

## File Uploads (Storage)

Proof-of-payment files uploaded by guests are stored on the `public` disk at `storage/app/public/proofs/` and served via `public/storage/proofs/`. Run the storage symlink command once after setup so the files are accessible:

```bash
php artisan storage:link
```

In production, configure `FILESYSTEM_DISK=public` or use an S3-compatible service via the `AWS_*` environment variables in `.env`.

---

## Running Tests

The project uses [Pest](https://pestphp.com) v3. Tests cover public routes, booking logic, admin CRUD, SEO endpoints, and authentication:

```bash
php artisan test
```

Expected result: **247 tests passed (632 assertions)**.

---

## Technology Overview

### Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 11 |
| Templating | Blade + Alpine.js (no Livewire) |
| CSS | Tailwind CSS v4 (compiled via Vite) |
| Database | SQLite (dev) / MySQL (production) |
| Testing | Pest v3 |
| Payments | Paystack (stub; live keys optional) |

### Project Structure

```
app/
  Http/
    Controllers/
      Admin/           # Admin CRUD controllers (rooms, apts, bookings, blog, ...)
      Concerns/        # Shared traits (ParsesListInput)
      Auth/            # Auth controllers (Breeze-generated)
    Requests/          # Form request validators
  Models/              # Eloquent models (Room, Apartment, Booking, BlogPost, ...)
  Services/
    BookingCalculator  # Quote logic: nights x price, 40% fee, balance due
resources/
  views/
    admin/             # Admin panel Blade views
    components/        # Reusable Blade components (layouts, cards, schema, SEO, ...)
    *.blade.php        # Public-facing pages
database/
  migrations/          # Schema definitions
  seeders/             # Demo data (rooms, apartments, blog posts, FAQs, admin user)
```

### Key Business Logic

- **`BookingCalculator::quote()`** — accepts `check_in`, `check_out`, `price_per_night`; returns `nights`, `total`, `commitment_fee` (40%), `balance_due`.
- **`EnsureAdmin` middleware** — protects all `/admin/*` routes; checks `user->is_admin === true`.
- **Schema components** (`resources/views/components/schema/`) — zero-JS JSON-LD output for SEO structured data.

---

## Contact

**Hotel Benizia**
1 Kingsley Emu Street, Summit Road, Asaba, Delta State, Nigeria
Phone: +234 813 406 2487
Email: booking@hotelbenizia.ng
