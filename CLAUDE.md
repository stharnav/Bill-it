# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Bill It** is a free, open-source web-based billing and business management system built with **Laravel 12**. It manages sales (invoices/refunds), products, categories, users, and company info with a session‑based auth system and an AdminLTE dashboard UI.

## Quick Start

```bash
# Install dependencies
composer install
npm install

# Environment
cp .env.example .env
# Edit .env: DB_DATABASE, DB_USERNAME, DB_PASSWORD, DB_CONNECTION (mysql/sqlite)
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Start dev server (Laravel + Queue + Vite concurrently)
composer run dev

# Default login: admin / password
```

## Commands

| Command | Description |
|---------|-------------|
| `composer run dev` | Start all dev services: `php artisan serve`, `queue:listen`, `npm run dev` |
| `php artisan serve` | Start Laravel dev server only |
| `npm run dev` | Start Vite HMR for frontend assets |
| `npm run build` | Build production assets |
| `php artisan migrate` | Run database migrations |
| `php artisan db:seed` | Seed database (default admin user + company) |
| `php artisan test` | Run all Pest tests |
| `php artisan make:test NameTest` | Create a new Pest test |
| `composer run test` | Run `config:clear` then `artisan test` |
| `./vendor/bin/pest` | Run Pest directly |
| `./vendor/bin/pint` | Run Laravel Pint (PSR‑12 code style fixer) |

## Architecture

### Database Schema (7 tables)

- **users** — `id`, `name`, `email`, `username`, `password`, `user_logo`, `user_type` (0=admin, 1=user), `status` (1=active, 0=inactive), timestamps
- **category** — `id`, `name`, `description`, timestamps
- **product** — `id`, `name`, `description`, `price` (decimal 10,2), `category_id` (FK→category), timestamps
- **sales** — `id`, `bill_no` (unique, e.g. `INV-00001`/`REF-00001`), `description`, `mode_of_payment` (1=Cash/2=Fonepay/3=Credit Card/4=Debit Card/5=Bank Transfer), `payment_details`, `discount`, `tax`, `customer_name`, `is_refund` (boolean), timestamps
- **sales_product** (pivot) — `id`, `sale_id`, `product_id`, `quantity`, `price` — **no FK constraints**
- **company** — `id`, `company_name`, `company_email`, `company_address`, `company_motto`, `company_phone_no`, `company_pan`, `company_registration_no`, `company_website`, `currency`, `company_logo`, timestamps
- **logs** — `id`, `user_id` (FK→users), `ip_address` (nullable, unused), `description`, `created_at` (only)

### Models & Relationships

| Model | Table | Key Relationships |
|-------|-------|------------------|
| `User` | users | (standalone — no explicit model relationships defined) |
| `Category` | category | `hasMany(Product)` |
| `Product` | product | `belongsTo(Category)` |
| `Sales` | sales | (standalone — no `hasMany(SalesProduct)` relationship defined) |
| `SalesProduct` | sales_product | `belongsTo(Sales)`, `belongsTo(Product)` |
| `Company` | company | (standalone — singleton pattern, single row) |
| `Log` | logs | `belongsTo(User)` |

**Notable gaps:** `Sales` model has no relationships defined. Reports controller uses raw `DB::table()` joins instead of Eloquent.

### Controllers (7)

All in `App\Http\Controllers`:

| Controller | CRUD | Key Methods |
|-----------|------|-------------|
| `UserController` | Users, Auth, Profile | `login()`, `logout()`, `welcome()` (dashboard), `store()`, `updateProfile()`, `updatePassword()`, `destroy()` |
| `ProductController` | Products | `index()`, `fetchCategories()`, `store()`, `edit()`, `update()`, `destroy()` |
| `CategoryController` | Categories | `index()`, `store()`, `edit()`, `update()`, `destroy()` |
| `SalesController` | Sales/Refunds | `index()`, `search()` (AJAX), `store()`, `bill()`, `refund()`, `processRefund()` |
| `SalesReportController` | Reports | `index()`, `search()` (date/category filter with `DB::table()` joins) |
| `CompanyController` | Company | `index()`, `store()`, `update()` (with logo upload) |
| `LogController` | Activity Logs | `index()` (logs with user eager load, latest first) |

### Routes (`routes/web.php`)

All authenticated routes behind `auth` middleware. Login/logout are public.

**Navigation structure:**
- **Dashboard** (`/`)
- **Sales** (`/sales`) — list, new sale (`/new-sales`), bill view (`/bill/{id}`), refund (`/sales/refund/{id}`)
- **Products** (`/products`) — list, add (`/add-product`), edit (`/product/{id}/edit`), delete
- **Categories** (`/category`) — list, add (`/add-category`), edit, delete
- **Reports** (`/sales-report`) — filtered sales report with date range
- **About** — profile (`/about-me`), company info (`/about-company`), users (`/about-users`), logs (`/about-logs`)

### Auth & Authorization

- Session-based authentication using Laravel's built-in system (`username` + `password`)
- `RoleMiddleware` checks `user_type` field: `0` = admin only (via `->middleware('role:admin')`), currently only applied to the `/add-user` route
- `App\Http\Middleware\RoleMiddleware` registered as `role` alias
- Inactive users (`status = 0`) are rejected at login

### Frontend

- **AdminLTE 3** (Bootstrap 4 based) for dashboard UI
- **Vite** with `@tailwindcss/vite` plugin for asset building (Tailwind installed but unused — all styling is AdminLTE)
- **Font Awesome** 5 icons
- **DataTables** with Buttons extension for table export
- Inline jQuery in Blade files (no extracted JS modules)
- Every view duplicates the full HTML shell (`<html>`, sidebar, footer, scripts) — no Blade layout inheritance

### Key Patterns

1. **Bill numbering** — `INV-{id padded 5}` for sales, `REF-{id padded 5}` for refunds, calculated from `Sales::latest()->first()->id + 1`
2. **Activity logging** — `Log::create()` called after every create/update/delete + login action (IP address not captured)
3. **Image uploads** — stored in `public/uploads/{user,company}/`, old files deleted on replacement
4. **Pest PHP** is the test framework (installed but no tests exist yet)
5. **Session/cache/queue** all use `database` driver

### PHP Version & Key Packages

- PHP 8.2+, Laravel 12
- `laravel/pint` (PSR‑12 code style)
- `pestphp/pest` + `pestphp/pest-plugin-laravel` (testing)
- `laravel/sail` (Docker dev environment available)
- `laravel/tinker` (REPL)

### Naming Conventions

- Routes: mixed naming — `sales.store`, `products.add-product`, `sales.new-sales`, `categories.category`
- Views: organized by feature in subdirectories (`sales/`, `products/`, `categories/`, `about/`, `auth/`, `layouts/`, `reports/`, `errors/`)
- Controllers: singular `{Feature}Controller`
- Models: singular `{Feature}` (some use custom table names matching the singular form, e.g. `product`, `category`)
