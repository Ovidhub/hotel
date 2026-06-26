# Hotel Benizia — Laravel SEO Redesign — Design Spec

**Date:** 2026-06-26
**Status:** Approved (pending final spec review)
**Source material:** Two React/Vite/TS/Tailwind design variants (`laravel-hotel-seo-redesign/` and `laravel-hotel-seo-redesign (1)/`) + live site https://hotelbenizia.ng/

---

## 1. Goal

Build one professional, production-grade **Laravel** website for **Hotel Benizia** (luxury hotel + serviced apartments in Asaba, Delta State, Nigeria), merging the best of the two supplied React designs, and engineered to be **SEO ranking ready**.

The two React folders are reference designs only. The deliverable is a server-rendered Laravel app — they are not run or shipped.

## 2. Locked Decisions

| Area | Decision |
|------|----------|
| Rendering | **Blade SSR** + Tailwind + Alpine.js (max crawlability, fast TTFB) |
| Scope | **Full system**: marketing site + DB-backed inventory + booking flow + admin panel |
| Database | **SQLite** (file-based; swappable to MySQL for prod) |
| Payments | **Stubbed, integration-ready** (commitment fee + bank transfer/proof + Paystack controller stub) |
| Admin auth | **Laravel Breeze** (Blade), one seeded admin user |
| Version control | git init + logical milestone commits |
| Laravel | v11, PHP 8.3, Vite asset pipeline |

## 3. Brand Foundation

- **Name / tagline:** Hotel Benizia — "Luxury in the heart of Asaba"
- **Palette:** deep green `#1D5C52`, gold `#C9A96E`, warm neutrals (cream/off-white backgrounds, charcoal text)
- **Type:** serif display for headings (Playfair Display / Cormorant), Inter for body
- **Contact (live-site = source of truth):**
  - Phone: `+234 813 406 2487` (`+2348134062487`)
  - Email: `booking@hotelbenizia.ng`
  - Address: 1 Kingsley Emu Street, Central Area, Asaba 320242, Delta State, Nigeria
  - Socials: Facebook, Twitter/X, Pinterest, YouTube, Instagram (footer)
- **Note:** design data files used older contact details (`+234 904 116 3278`, `info@hotelbenizia.ng`). Live-site values win.

### Design merge strategy
- From variant `(1)`: richer data model, **HB Apartments**, payment methods + **commitment-fee booking policy**, FAQ, restaurant menu, JSON-LD `Hotel` schema, gallery alt text.
- From the first variant: fuller page set, **admin dashboard**, checkout/cart/success flow, room detail depth (amenities/policies/includes), blog detail.
- Visual language: the shared luxury African-modern aesthetic (green+gold), editorial serif headings, generous whitespace, image-forward sections, subtle reveal-on-scroll.

## 4. Site Map

**Public:**
`/` Home · `/rooms` + `/rooms/{slug}` · `/apartments` + `/apartments/{slug}` · `/restaurant` · `/gallery` · `/events` · `/blog` + `/blog/{slug}` · `/about` · `/contact` · `/faq`

**Booking flow:**
`/book/{type}/{slug}` (booking form) → `/checkout` (commitment fee + payment method) → `/booking/success/{ref}`

**Admin (auth, prefix `/admin`):**
`/admin/login` · `/admin` (dashboard) · `/admin/rooms` · `/admin/apartments` · `/admin/bookings` · `/admin/payment-methods` · `/admin/blog` · `/admin/testimonials` · `/admin/faqs` · `/admin/messages`

**SEO endpoints:** `/sitemap.xml` · `/robots.txt`

## 5. Data Model (migrations + Eloquent + seeders)

- **rooms**: id, slug(unique), name, category, price(int, NGN), price_label, size, guests, beds, baths, sqm, rating, reviews, excerpt, description(long), image, gallery(json), amenities(json), includes(json), policies(json), best_for(json), is_active, sort
- **apartments**: id, slug(unique), name, type, price, price_label, status(enum Available/Occupied/Maintenance), image, gallery(json), bedrooms, bathrooms, occupancy, description, amenities(json), is_active
- **bookings**: id, ref(unique, e.g. HB-#####), bookable_type, bookable_id (morphs to room/apartment), guest_name, guest_email, guest_phone, check_in, check_out, nights, guests, total, commitment_percent, commitment_fee, balance_due, status(enum: Pending Payment/Confirmed/Checked In/Cancelled), payment_method_id(nullable), proof_path(nullable), notes, timestamps
- **payment_methods**: id, name, type(enum: Bank Transfer/Card Gateway/USSD/POS/Cash), provider, account_name, account_number, bank_name, instructions, active, accepts_commitment_fee, sort
- **blog_posts**: id, slug(unique), title, excerpt, body(long), category, category_color, image, author, published_at
- **testimonials**: id, name, role, rating, text, avatar
- **faqs**: id, question, answer, sort
- **messages**: id, name, email, phone, subject, message, read_at (contact form submissions)
- **users**: standard Breeze + is_admin flag; one seeded admin (`admin@hotelbenizia.ng`)

All tables seeded from the merged design data (5 rooms, 3 apartments, payment methods, blog posts, testimonials, FAQs) so the site is content-complete on `migrate:fresh --seed`.

### Settings / global content
A `config/hotel.php` (or `site_settings` seeded table) holds hotel name, contact, socials, ticker amenity items, facilities list, restaurant menu, hero images, and booking policy (commitment %, cancellation window) — single source of truth for header/footer/schema.

## 6. Booking Logic

- Commitment-fee model: **40% commitment fee** secures booking; balance due at check-in; **24h cancellation window**.
- Flow: select room/apartment → dates + guests (nights & total computed) → guest details → choose payment method → on submit create `booking` (status *Pending Payment*) with generated ref → success page shows ref, commitment fee, balance, bank details / next steps.
- Payment options: **Bank Transfer** (show account, allow proof-of-payment upload) and **Paystack** via `PaystackController` stub with clearly marked TODO for live `PAYSTACK_PUBLIC_KEY`/`PAYSTACK_SECRET_KEY` (init + verify methods scaffolded, no real call without keys).
- Validation: dates required, check_out > check_in, guests within capacity, required contact fields.

## 7. Admin Panel

- Breeze login; `admin` middleware guards `/admin/*`.
- Dashboard: stat cards (today revenue, occupancy %, open bookings, available units) + recent bookings table + a light Chart.js revenue/occupancy mini-chart.
- CRUD: Rooms, Apartments, Payment Methods, Blog, Testimonials, FAQs (create/edit/delete + active toggles).
- Bookings: list, filter by status, view detail, update status, view uploaded proof.
- Messages: list contact submissions, mark read.

## 8. SEO Layer (headline requirement)

- **`<x-seo>` Blade component**: per-page title, meta description, canonical, robots, Open Graph (og:title/description/image/type/url), Twitter card. Sensible site-wide defaults overridable per page.
- **JSON-LD structured data** (`<x-schema>` partials):
  - Site-wide: `Hotel` + `LocalBusiness` with `PostalAddress`, `geo`, `telephone`, `priceRange`, `amenityFeature`, `aggregateRating`.
  - Room/apartment detail: `Product`/`HotelRoom` + `Offer` (price, NGN, availability).
  - Blog detail: `Article`/`BlogPosting`.
  - FAQ page: `FAQPage`.
  - Breadcrumbs: `BreadcrumbList` on inner pages.
- **`/sitemap.xml`** generated from rooms, apartments, blog posts, and static pages; **`/robots.txt`** allowing crawl + sitemap reference.
- **On-page**: semantic HTML5 landmarks, single H1 per page, descriptive image alt text, lazy-loaded images, width/height to limit CLS, clean human-readable slugs, fast Tailwind/Vite production build, mobile-first responsive, accessible color contrast.
- **Local SEO copy**: titles/descriptions/H1s targeting "hotel in Asaba", "luxury hotel Delta State", "serviced/short-let apartments Asaba", "event venue Asaba", woven naturally into real Benizia content.
- Canonical host `https://hotelbenizia.ng`; configurable `APP_URL`.

## 9. Component / View Architecture

- `layouts/app.blade.php` (public shell: header nav, sticky CTA, amenity ticker, footer, SEO/schema slots).
- `layouts/admin.blade.php` (sidebar admin shell).
- Reusable Blade components: `x-seo`, `x-schema.*`, `x-room-card`, `x-apartment-card`, `x-section-intro`, `x-page-hero`, `x-reveal` (Alpine scroll reveal), `x-testimonial`, `x-cta`, `x-rating-stars`, `x-amenity-ribbon`.
- Controllers: `HomeController`, `RoomController`, `ApartmentController`, `RestaurantController`, `GalleryController`, `EventController`, `BlogController`, `PageController` (about/contact/faq), `ContactController`, `BookingController`, `CheckoutController`, `PaystackController`, `SitemapController`; `Admin\*` controllers per resource.

## 10. Quality / Acceptance

- `php artisan migrate:fresh --seed` yields a fully populated, browsable site + working admin login.
- Feature tests: every public route returns 200; room/apartment detail renders seeded content; booking submission persists a record with correct commitment fee and generated ref; `/admin/*` redirects guests to login and loads for admin; `/sitemap.xml` and `/robots.txt` return valid output; SEO component renders title + JSON-LD.
- README: setup, seed, admin credentials, where to add Paystack keys, how to switch to MySQL.
- Mobile-first responsive verified for key pages.

## 11. Out of Scope (YAGNI)

- Real payment processing / live Paystack keys (stub only).
- Guest accounts / login (booking is guest-checkout only).
- Multi-language, multi-currency.
- Email deliverability infra (contact/booking mail uses Laravel default `log`/`mail` driver, configurable).
- Migrating/keeping the React apps.

## 12. Environment

PHP 8.3.30, Composer 2.9.5, Node 20.20, npm 11.12, Git 2.53 — all present. SQLite via PHP's bundled driver (no MySQL server required). Windows 10 / PowerShell primary shell.
