# Deploying Black Tower Hotels Asaba (WhoGoHost cPanel)

Black Tower Hotels Asaba (`blacktowerhotelsasaba.com`) is deployed from the
**same repo** as Hotel Benizia, but as a **completely separate site**: its own
domain, its own MySQL database, its own `.env`, and its own admin user. The
only thing that differentiates it at runtime is one line in its `.env`:
`THEME=blacktower`. Follow this checklist top to bottom for a first-time
deploy; see [Redeploying later](#redeploying-later-after-code-changes) for
subsequent pushes.

This mirrors the existing Hotel Benizia deploy (see `WHOGOHOST-DEPLOY.md`) —
same shared-cPanel pattern, different domain/database/`.env`. Read that file
first if you haven't deployed this app before; this doc only calls out what's
different for Black Tower.

---

## 0. What you need before starting

1. **Domain**: `blacktowerhotelsasaba.com` pointed at the WhoGoHost server
   (nameservers/DNS, or added as an Addon Domain in cPanel if it shares
   hosting with another site). It currently serves the old WordPress site —
   deploying replaces it (back up the old WP files + DB first if you want a
   copy of the source photos/content).
2. **PHP 8.2 or 8.3** selected for the domain (cPanel → *MultiPHP Manager*).
3. **A dedicated MySQL database + user for Black Tower** (cPanel → *MySQL®
   Databases*) — **do not reuse the Hotel Benizia database.** Create:
   - a new database (e.g. `<cpaneluser>_blacktower`)
   - a new database user (e.g. `<cpaneluser>_bt_admin`) with a strong password
   - add the user to the database with **All Privileges**
4. **An email account** for booking-inquiry notifications, e.g.
   `contact@blacktowerhotelsasaba.com` (cPanel → *Email Accounts*).
5. **Terminal/SSH access** (cPanel → *Terminal*, or SSH). Required to run
   `php artisan` commands — there is no web-only fallback for migrations.

---

## 1. Build locally (no Node on the server)

From `site/` on your machine:

```bash
NODE_OPTIONS="--max-old-space-size=4096" npm run build
```

Confirm `public/build/manifest.json` contains `app.css`, `app.js`, and
`blacktower.css` entries (these were verified present as part of this task).
The Black Tower theme's `blacktower.css` bundle is only loaded when
`THEME=blacktower`, so it must be in the uploaded `public/build/` — the
server never runs Vite.

---

## 2. Upload & arrange files

Same split-layout pattern as the Benizia deploy: the framework lives **outside**
`public_html`, and only the contents of `public/` are exposed to the web, either
via the public_html-move method or by pointing the domain's document root
directly at the app's `public/` folder.

1. Package the app **excluding** `node_modules`, `.git`, local SQLite, and
   raw photo source folders, but **including** `vendor/` and `public/build/`.
2. Upload to a private folder above the docroot, e.g. `/home/<user>/blacktower`
   (framework: `app/`, `bootstrap/`, `config/`, `routes/`, `vendor/`, etc.).
3. Point the domain's docroot at `~/blacktower/public`:
   - **Preferred (Addon Domain / subdomain with its own docroot):** cPanel →
     *Domains* → set Document Root to `/home/<user>/blacktower/public`. No
     symlink juggling needed.
   - **If Black Tower must live under `public_html`** (e.g. it's the primary
     domain on this cPanel account): move the *contents of*
     `~/blacktower/public/` into `public_html/`, delete the emptied
     `public` dir, and edit `public_html/index.php`'s two `require` paths to
     point at `../blacktower/vendor/autoload.php` and
     `../blacktower/bootstrap/app.php` — exactly as documented in
     `WHOGOHOST-DEPLOY.md` §2 for Benizia. Also re-point the `storage` symlink:
     `ln -s ~/blacktower/storage/app/public ~/public_html/storage`.
4. Make sure `storage/` and `bootstrap/cache/` are writable:
   ```bash
   chmod -R 775 ~/blacktower/storage ~/blacktower/bootstrap/cache
   ```
5. Explicitly re-upload (or `scp`) `public/img/themes/blacktower/` and
   `public/build/` if your bundle/zip step excluded them — these are the
   theme's images and compiled assets and must exist on the server.

---

## 3. `.env` for Black Tower

In the framework folder (`~/blacktower/.env` or wherever step 2 placed it),
create a `.env` distinct from Benizia's. Identity fields are env-driven
(`config/hotel.php` reads them via `env(...)` with Benizia's values as
fallback defaults), so setting these here fully overrides the defaults for
this deploy:

```
APP_NAME="Black Tower Hotels Asaba"
APP_ENV=production
APP_KEY=                                  # run `php artisan key:generate` once uploaded
APP_DEBUG=false
APP_URL=https://blacktowerhotelsasaba.com

THEME=blacktower

HOTEL_NAME="Black Tower Hotels Asaba"
HOTEL_PHONE="+234 912 793 6399"
HOTEL_PHONE_HREF=+2349127936399
HOTEL_EMAIL=contact@blacktowerhotelsasaba.com
HOTEL_ADDRESS="78 Anwai Road, Asaba, Delta State, Nigeria"
HOTEL_CANONICAL=https://blacktowerhotelsasaba.com

# ── SEO / structured-data identity (schema.org, sitemap, OG) ──
HOTEL_DESCRIPTION="Black Tower Hotels Asaba offers premium comfort, elegant rooms, and exceptional hospitality in Asaba, Delta State."
HOTEL_STREET="78 Anwai Road"
HOTEL_CITY=Asaba
HOTEL_REGION="Delta State"
HOTEL_COUNTRY=NG
# HOTEL_POSTAL=            # set if known
# Coordinates for 78 Anwai Road, Asaba (update to the exact location):
HOTEL_GEO_LAT=6.2059
HOTEL_GEO_LNG=6.7261
HOTEL_MAPS_URL="https://www.google.com/maps?q=Black+Tower+Hotels+Asaba"

# ── Database (the dedicated DB created in §0.3 — NOT the Benizia one) ──
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<cpaneluser>_blacktower
DB_USERNAME=<cpaneluser>_bt_admin
DB_PASSWORD=<the db user's password>

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

# ── Mail (the Black Tower email account from §0.4) ──
MAIL_MAILER=smtp
MAIL_HOST=mail.blacktowerhotelsasaba.com
MAIL_PORT=465
MAIL_USERNAME=contact@blacktowerhotelsasaba.com
MAIL_PASSWORD=<that email account's password>
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="contact@blacktowerhotelsasaba.com"
MAIL_FROM_NAME="${APP_NAME}"

FILESYSTEM_DISK=local
VITE_APP_NAME="${APP_NAME}"
```

If you keep card payments disabled for Black Tower, leave the `PAYSTACK_*`
keys blank as in the Benizia `.env` — the app degrades gracefully.

> **Do not** copy Benizia's live `.env` and edit it in place on a shared
> checkout of this repo — always create Black Tower's `.env` directly in
> Black Tower's own framework folder on the server, and Benizia's `.env`
> directly in Benizia's. The repo itself has no `.env` committed; each deploy
> owns its own.

---

## 4. Run the setup commands (cPanel Terminal / SSH)

```bash
cd ~/blacktower              # or wherever the framework was uploaded

php artisan key:generate                        # only if APP_KEY is blank
php artisan migrate --force                      # create tables (booking_inquiries, rooms, etc.)
php artisan db:seed --class=BlackTowerRoomSeeder --force   # seed Black Tower's 4 rooms
php artisan blacktower:import-photos             # (re)fetch/localize the theme's images
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Do not** run `php artisan migrate --force --seed` (the default seeder)
here — that runs `DatabaseSeeder`, which is wired for Benizia's rooms/
apartments/admin. Seed Black Tower explicitly with `BlackTowerRoomSeeder` as
above, and create its admin user separately (§5).

**Timing gotcha for `blacktower:import-photos`:** this command downloads its
source images from `https://blacktowerhotelsasaba.com` itself (the *old*
WordPress site's `wp-content` uploads) and re-encodes them locally under
`public/img/themes/blacktower/`. The theme's images are already committed to
the repo and uploaded as part of §2, so **this step is an optional refresh,
not a hard requirement.** If you do run it, run it **only while
`blacktowerhotelsasaba.com` DNS still points at the old WordPress site** —
once you cut the domain over to this Laravel app (§0.1/§2), running the
importer again would have the domain fetch images from itself and fail or
produce garbage. (This is the same "dead wp-content URL" trap documented for
Hotel Benizia — see `WHOGOHOST-DEPLOY.md`.) If the old WordPress source is
already gone, skip this command; the committed images are already in place.

---

## 5. Create the Black Tower admin user

There is no seeded admin for Black Tower (`AdminUserSeeder` is Benizia-specific
— it hardcodes `admin@hotelbenizia.ng`). Create one via `tinker` on the server:

```bash
php artisan tinker --execute="
\$u = new \App\Models\User(['name'=>'Black Tower Admin','email'=>'admin@blacktowerhotelsasaba.com','password'=>Hash::make('CHANGE-ME-immediately')]);
\$u->save();
\$u->is_admin = true;
\$u->save();
echo 'admin created: '.\$u->email;
"
```

Log in at **`https://blacktowerhotelsasaba.com/login`**, then change the
password immediately from Admin → Account (`/admin/account`).

---

## 6. HTTPS

cPanel → **SSL/TLS Status** → run **AutoSSL** (Let's Encrypt) for
`blacktowerhotelsasaba.com`. `APP_URL` is already `https://...`, so Laravel
will generate https URLs once the certificate is issued.

---

## 7. Post-deploy verification

Check all of the following on `https://blacktowerhotelsasaba.com`:

- [ ] Homepage renders the **Black Tower theme** — coral/dark/cream palette,
      "Experience Comfort" hero — not the default Benizia flat theme. If you
      see Benizia's layout instead, `THEME=blacktower` didn't take effect:
      re-check `.env` and re-run `php artisan config:cache`.
- [ ] Rooms, About, and Contact pages load without errors and show the
      Black Tower room set (from `BlackTowerRoomSeeder`).
- [ ] Submit a **test booking inquiry** from the public contact/booking form,
      then confirm it appears under **Admin → Booking Inquiries**
      (`/admin/booking-inquiries`) and that the notification email arrives at
      `contact@blacktowerhotelsasaba.com`.
- [ ] All images load — open devtools/Network and confirm there are **no
      404s pointing at `wp-content`** (that would mean an image reference
      leaked to the old WordPress host instead of the localized
      `public/img/themes/blacktower/` copy).
- [ ] Confirm the routes Black Tower doesn't offer return **404** (not Benizia
      content): visit `/restaurant`, `/apartments`, `/gallery`, `/events`,
      `/blog`, `/faq` — each should 404. This is enforced automatically by the
      `RestrictThemeRoutes` middleware whenever `THEME` is a non-default theme.
- [ ] `view-source:` the homepage and spot-check the canonical `<link>` /
      Open Graph / sitemap / JSON-LD schema URLs — they should all show
      `blacktowerhotelsasaba.com` and Black Tower's address/description/geo.
      These are env-driven (`HOTEL_CANONICAL`, `HOTEL_DESCRIPTION`,
      `HOTEL_STREET`/`HOTEL_CITY`/`HOTEL_REGION`/`HOTEL_COUNTRY`,
      `HOTEL_GEO_LAT`/`HOTEL_GEO_LNG`, `HOTEL_MAPS_URL`), so confirm those
      are set in this deploy's `.env` (§4). The JSON-LD `image` list is
      theme-aware and points at `/img/themes/blacktower/*` automatically.

---

## 8. Hotel Benizia is unaffected

Benizia's deploy needs **no changes**. Its `.env` has no `THEME` variable, and
`config('hotel.theme')` defaults to `'default'` when `THEME` is unset, so
`hotelbenizia.ng` keeps serving the existing flat (non-themed) views exactly
as before. The two sites share only the codebase, not the database, `.env`,
or domain — deploying or redeploying one never touches the other, as long as
each is uploaded to its own framework folder (`~/benizia` vs `~/blacktower`)
with its own `.env`.

---

## Redeploying later (after code changes)

```bash
# locally
NODE_OPTIONS="--max-old-space-size=4096" npm run build

# upload changed files to ~/blacktower, then on the server:
cd ~/blacktower
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
