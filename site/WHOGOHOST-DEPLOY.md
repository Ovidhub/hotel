# Deploying Hotel Benizia to WhoGoHost (Shared cPanel)

This guide deploys the Laravel app to a **shared cPanel** plan on WhoGoHost.
The upload bundle already includes `vendor/` and the compiled `public/build/`
assets, so **you do NOT need Composer or Node.js on the server** — only a way
to run a few `php artisan` commands (cPanel **Terminal** or **SSH**).

---

## 0. What I need from you / what to prepare

Have these ready (create the secrets directly on the server — don't share them):

1. **Domain**: `hotelbenizia.ng` pointed to WhoGoHost (nameservers/DNS). Note it
   currently serves the old WordPress site — deploying replaces it (back it up first).
2. **PHP 8.2 or 8.3** selected for the domain (cPanel → *MultiPHP Manager*).
3. **A MySQL database + user** (cPanel → *MySQL® Databases*) — you'll get:
   database name, username, password.
4. **An email account** for notifications, e.g. `booking@hotelbenizia.ng`
   (cPanel → *Email Accounts*) — you'll need its password + the mail server host.
5. **Terminal/SSH access.** Most WhoGoHost cPanel plans have cPanel → *Terminal*.
   If you don't see it, open a WhoGoHost support ticket to **enable SSH** — it makes
   this much easier. (There's a no-terminal fallback in §6.)

---

## 1. The upload bundle

You have `site-deploy.zip` (created next to the project). It contains the whole
Laravel app **minus** `node_modules`, `.git`, local SQLite, and raw photo folders,
but **including** `vendor/` and `public/build/`.

Inside it, the app has the standard Laravel layout with a `public/` folder.

---

## 2. Upload & arrange files (public_html method)

On the primary domain, cPanel serves from `public_html`, so we split the app:
the framework lives in a private folder and only `public/` goes into `public_html`.

1. cPanel → **File Manager**.
2. Go **above** `public_html` (your home dir, e.g. `/home/benizia`). Upload
   `site-deploy.zip` there and **Extract** it. Rename the extracted folder to
   **`benizia`** (so you have `/home/benizia/benizia`).
3. Move the **contents of** `/home/benizia/benizia/public/` **into** `public_html/`
   (select all inside `public`, Move → `public_html`). Then delete the now-empty
   `benizia/public`.
   - Result: `public_html/index.php`, `public_html/build/`, `public_html/img/`,
     `public_html/.htaccess`, `public_html/robots.txt` … and the framework at
     `/home/benizia/benizia/` (app, vendor, routes, etc.).
4. Edit **`public_html/index.php`** and update the two paths to point at the
   framework folder:
   ```php
   require __DIR__.'/../benizia/vendor/autoload.php';
   $app = require_once __DIR__.'/../benizia/bootstrap/app.php';
   ```
   (Change `/../vendor/...` → `/../benizia/vendor/...` and
   `/../bootstrap/app.php` → `/../benizia/bootstrap/app.php`.)

> **Alternative (if your cPanel lets you set the domain's Document Root):**
> upload the whole app to `/home/benizia/benizia` and set the domain document
> root to `/home/benizia/benizia/public` (cPanel → *Domains*). Then skip steps 3–4.

---

## 3. Create the database & .env

1. cPanel → **MySQL® Databases**: create a database, a user, add the user to the
   database with **All Privileges**. Note the final names (cPanel prefixes them,
   e.g. `benizia_hotel`, `benizia_admin`).
2. In File Manager, in `/home/benizia/benizia/`, rename **`.env.production.example`**
   to **`.env`** and edit it:
   - `APP_URL=https://hotelbenizia.ng`
   - `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` = the values from step 1
   - `MAIL_HOST` / `MAIL_USERNAME` / `MAIL_PASSWORD` = your email account
     (host is usually `mail.hotelbenizia.ng`; port `465` + `ssl`, or `587` + `tls`)
   - `APP_KEY` is already set (keep it), `APP_DEBUG=false`

---

## 4. Run the setup commands (cPanel Terminal / SSH)

Open cPanel → **Terminal** and run (adjust the path if different):

```bash
cd ~/benizia/benizia

# point PHP at v8.2/8.3 if needed (WhoGoHost often aliases it as ea-php83):
# alias php=/opt/cpanel/ea-php83/root/usr/bin/php

php artisan migrate --force --seed      # create tables + seed rooms/apartments/admin
php artisan storage:link                # link storage for proof-of-payment uploads
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Fix the storage symlink for the public_html layout.** Because `public` moved to
`public_html`, recreate the link so uploaded payment proofs are reachable:

```bash
rm -f ~/public_html/storage
ln -s ~/benizia/benizia/storage/app/public ~/public_html/storage
```

Make sure these are writable:

```bash
chmod -R 775 ~/benizia/benizia/storage ~/benizia/benizia/bootstrap/cache
```

---

## 5. HTTPS

cPanel → **SSL/TLS Status** → run **AutoSSL** (Let's Encrypt) for the domain so
`https://hotelbenizia.ng` is secured. Laravel is set to `https` via `APP_URL`.

---

## 6. If you have NO Terminal/SSH (fallback)

The commands in §4 are required (migrations, storage link, caches). Options:
- **Best:** open a WhoGoHost ticket to enable **SSH**, then do §4.
- Some plans expose a terminal via cPanel → *Setup PHP App* / *Application Manager* —
  use its "Run Composer/Command" area to run the `php artisan …` lines.
- Absolute last resort: tell me and I'll generate a one-time, protected setup route
  you hit once in the browser to run migrate/seed/link, then remove.

---

## 7. Scheduler (Booking.com hourly iCal sync)

cPanel → **Cron Jobs** → add (every minute; Laravel decides what runs):

```
* * * * * /opt/cpanel/ea-php83/root/usr/bin/php /home/benizia/benizia/artisan schedule:run >> /dev/null 2>&1
```

(Use the correct PHP binary path for your selected version.)

---

## 8. Go live over the old WordPress

1. **Back up** the current WordPress files + DB (cPanel → *Backup*) first.
2. Remove/rename the old WordPress files in `public_html`, then do §2.
3. Test thoroughly before announcing.

---

## 9. After deployment — do these

- Log in at `https://hotelbenizia.ng/admin` with **admin@hotelbenizia.ng / password**
  and **change the password immediately** (Profile).
- **Set the real bank account**: Admin → Payment Methods → *Hotel Benizia Bank Transfer*
  (account name/number/bank) — the seeded number is a placeholder.
- Send a test booking; confirm the **"received"** and **"approved"** emails arrive.
- In **Google Search Console**, submit `https://hotelbenizia.ng/sitemap.xml`.
- (Optional) add live **Paystack** keys to `.env` for card payments.

---

## 10. Troubleshooting

- **500 error / blank page:** temporarily set `APP_DEBUG=true` in `.env`, reload,
  read the message, then set it back to `false`. Usually a wrong DB credential,
  a missing `.env`, or `storage`/`bootstrap/cache` not writable (§4 `chmod`).
- **CSS/JS not loading:** ensure `public_html/build/` exists and `APP_URL` is https.
- **Images/proofs 404:** the `public_html/storage` symlink (§4) or `public_html/img`
  didn't upload — re-check §2/§4.
- After changing `.env`, run `php artisan config:cache` again.

---

### Redeploying later (after code changes)
Rebuild the bundle locally (`npm run build`), upload changed files, then:
```bash
cd ~/benizia/benizia && php artisan migrate --force && php artisan config:cache && php artisan view:cache
```
