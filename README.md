[<img src="https://s3.amazonaws.com/creativetim_bucket/products/166/original/opt_blkp_laravel_thumbnail.jpg" width="100%" />](https://www.creative-tim.com/live/black-dashboard-pro-laravel) 


# Subscribify · Multitenant Billing SaaS (Laravel + Argon UI)

[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777bb3?logo=php)]() [![Laravel](https://img.shields.io/badge/Laravel-10%2F11-f9322c?logo=laravel)]() [![MySQL](https://img.shields.io/badge/MySQL-8-4479a1?logo=mysql)]() [![Stripe](https://img.shields.io/badge/Stripe-Cashier-635bff?logo=stripe)]()

A portfolio-grade SaaS that demonstrates **auth**, **multi-tenancy**, **RBAC**, **feature flags**, **Stripe billing**, and **audit logging** — built with **Laravel 10/11**, **MySQL 8**, and **Creative Tim Argon Dashboard (Bootstrap)**.

> Built by [@earl-cod3](https://github.com/earl-cod3)

---

## ✨ Highlights

- **Tenant routing:** `/t/{tenant}/…` with middleware that **binds the current tenant** and verifies membership.
- **RBAC:** `OWNER · ADMIN · STAFF · USER` enforced with **Gates/Policies** (controller-level).
- **Plans & features:** `free | pro | business` → feature flags (e.g., `reports`, `api`).
- **Billing:** **Stripe Cashier** (Checkout + Billing Portal) with **verified webhooks** and idempotency.
- **Audit log:** records sensitive actions (billing, role changes, deletes).
- **Argon UI:** clean Blade layout; sidebar links are tenant-aware.

---

## 🧭 Architecture (at a glance)

```
Browser
   │  GET /t/{tenant}/dashboard
   ▼
Route (routes/web.php)
   │  -> middleware('tenant') binds current tenant and checks membership
   ▼
Controller (DashboardController@index)
   │  -> queries Eloquent models scoped by tenant_id
   ▼
Model (Eloquent)
   │  -> optional global scope for tenant_id
   ▼
View (Blade / Argon components)
```

**Security non-negotiables**

- Never trust `tenant_id` from client; use **route param `{tenant}` + membership check** in middleware.
- **Authorize in controllers** (`Gate::authorize`) — don’t rely on hidden menu items.
- **CSRF** on forms, **rate-limit** auth & webhooks, **validate** all input.
- **Stripe webhooks** verified (secret) + idempotent processing.
- **Idempotent seeders** (`updateOrInsert`) + **unique indexes** where appropriate.

---

## 📦 Tech & Versions

- PHP **8.3/8.4**, Laravel **10/11**
- MySQL **8**, Redis **(optional)** for queues/cache
- Stripe via **laravel/cashier**
- Testing: **Pest/PHPUnit**
- UI: **Argon Dashboard (Bootstrap)** + Blade
- Windows dev verified with **PHP 8.4.13** CLI

---

## 🗃️ Database (MVP ERD)

```
tenants (id, name, slug [uniq], owner_user_id, stripe_id?, trial_ends_at?, ...)
tenant_user (id, tenant_id [fk], user_id [fk], role, timestamps, unique [tenant_id,user_id])
features (id, slug [uniq])
plan_features (id, plan_code, feature_id [fk], unique [plan_code,feature_id])
audits_extra (id, tenant_id?, actor_id?, action, meta JSON, timestamps, index [tenant_id,created_at])

# App tables (example): items, categories, tags ... each has tenant_id [index/fk]
```

---

## 🧪 Core Routes

```txt
GET  /login                          → auth
POST /first-tenant                   → create first org for the user (OWNER)
GET  /dashboard                      → redirect to latest tenant
GET  /t/{tenant}/dashboard           → tenant dashboard
GET  /t/{tenant}/billing             → billing (OWNER/ADMIN)
POST /t/{tenant}/checkout            → Stripe Checkout
POST /stripe/webhook                 → Cashier Webhook (verified)
```

---

## ⚙️ Setup (Windows · your stack)

```powershell
# 0) clone
git clone https://github.com/earl-cod3/subscribify.git
cd subscribify

# 1) PHP deps (use your PHP 8.4 path)
& "C:\php-8.4.13\php.exe" .\composer.phar install

# 2) Node assets
npm install
npm run dev

# 3) Environment
copy .env.example .env
# edit .env:
# APP_URL=http://127.0.0.1:8000
# DB_DATABASE=subscribify, DB_USERNAME=root, DB_PASSWORD=
# STRIPE_* = your test keys
# CASHIER_MODEL=App\Models\Tenant

# 4) App key, DB, storage
& "C:\php-8.4.13\php.exe" artisan key:generate
# make sure database 'subscribify' exists
& "C:\php-8.4.13\php.exe" artisan migrate --seed
& "C:\php-8.4.13\php.exe" artisan storage:link

# 5) Run
& "C:\php-8.4.13\php.exe" artisan serve
# http://127.0.0.1:8000
```

### Setup (Linux/Mac)

```bash
composer install
npm install && npm run dev
cp .env.example .env
# edit APP_URL/DB/STRIPE keys
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

---

## 🔑 Environment (.env template)

```env
APP_NAME=Subscribify
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=subscribify
DB_USERNAME=root
DB_PASSWORD=

# Stripe / Cashier
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx
CASHIER_MODEL=App\Models\Tenant
```

---

## 👤 Seed data (for quick demo)

After `migrate --seed`, add your own users normally or include the optional **DemoSeeder**:

- **owner@example.com / password**
- First-run button → **POST** `/first-tenant` → redirects to `/t/{slug}/dashboard`

---

## 🧩 How multi-tenancy works here

- **URL pattern:** `/t/{tenant}/…`
- **Middleware `tenant`:**
  - loads tenant by slug,
  - verifies current user is a **member** via `tenant_user`,
  - binds it to the container → use `tenant()` helper anywhere in the request.
- **Queries:** always scoped by `tenant_id` _(or add a global scope on models)_.
- **RBAC:** we store role on the **pivot** (`tenant_user.role`), then:
  ```php
  Gate::define('manage-billing', function ($user) {
      $role = tenant()->users()->where('users.id',$user->id)->value('role');
      return in_array($role, ['OWNER','ADMIN'], true);
  });
  ```

---

## 🖥️ Folder structure (key bits)

```
app/
  Http/
    Controllers/ (DashboardController, BillingController, TenantSetupController)
    Middleware/  (SetCurrentTenant)
  Models/        (Tenant, Item, ...)
  Providers/     (AuthServiceProvider)
database/
  migrations/    (*_create_tenants_..., *_add_unique_index_roles_name.php, ...)
  seeders/       (RoleSeeder idempotent with updateOrInsert)
resources/
  views/         (layouts, pages/, Argon templates, billing/dashboard blades)
routes/
  web.php        (tenant route group + first-tenant route)
```

---

## 🧠 Examples you can reuse

**Count items per tenant for dashboard**
```php
$itemCount = \App\Models\Item::where('tenant_id', tenant()->id)->count();
return view('pages.dashboard', compact('itemCount'));
```

**Create a tenant and attach owner (first-run)**
```php
$tenant = Tenant::create([
  'name' => "{$user->name}'s Org",
  'slug' => Str::slug($user->name.'-org-'.Str::random(5)),
  'owner_user_id' => $user->id,
]);
$tenant->users()->syncWithoutDetaching([$user->id => ['role' => 'OWNER']]);
```

**Stripe Checkout (Cashier)**
```php
return tenant()->newSubscription('default', $request->price_id)->checkout([
  'success_url' => route('tenant.billing', tenant()->slug).'?success=1',
  'cancel_url'  => route('tenant.billing', tenant()->slug).'?canceled=1',
]);
```

---

## 🧪 Testing (suggested)

- Feature tests:
  - Non-member access to `/t/{slug}/*` → **403**
  - Billing gate only for OWNER/ADMIN
  - Dashboard shows tenant-scoped counts
- Use factories + seeders, and **Pest** for readability.

---

## 🛠️ Troubleshooting

- **`.env` missing / APP_KEY empty** → copy `.env.example`, run `php artisan key:generate`.
- **DB “Access denied for user 'forge'”** → set your own `DB_*` in `.env`.
- **PSR-4 autoload error** → each controller must be in **its own file** under `app/Http/Controllers`.
- **Assets 404** → run `npm run dev` (or `build`) and ensure correct paths in Blade.
- **Webhook 419/403** → ensure route is **POST** and **excluded** from CSRF if needed; set `STRIPE_WEBHOOK_SECRET`.

---

## 🧩 Deployment notes (quick)

- Set `APP_ENV=production`, `APP_DEBUG=false`.
- Run: `php artisan config:cache && php artisan route:cache && php artisan view:cache`.
- Run queues if using async jobs: `php artisan queue:work`.
- Keep `.env` out of git; rotate real secrets; never change `APP_KEY` on a live app.

---

## 💬 Interview talking points (what this repo proves)

- Implemented **tenant isolation** via middleware + DB scoping.
- Enforced **RBAC** with Gates/Policies at controller layer.
- Designed **idempotent seeders** + **unique indexes** (clean schema).
- Integrated **Stripe Cashier** with **verified, idempotent webhooks**.
- Used **Blade + Bootstrap (Argon)** for an accessible dashboard UI.

---

## 🗺️ Roadmap

- [ ] Invitations & tenant member management UI  
- [ ] Seat/usage-based billing & invoices PDF  
- [ ] Redis queues + Horizon dashboard  
- [ ] Scheduled email reports  
- [ ] API tokens (Sanctum) + minimal public API

---

## 📝 License

Copyright (c) 2025 earl-cod3

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights 
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is 
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all 
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE 
SOFTWARE.
