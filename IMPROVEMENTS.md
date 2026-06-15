# Billing System — Improvements & Features Document

## ✅ What Was Fixed

### 🔴 Critical Bug Fixes

| Fix | File | Change |
|-----|------|--------|
| **Dead route processRefund()** | `SalesController.php` | Added `processRefund()` method — creates refund record (REF-XXXXX), links products, logs activity, redirects to bill |
| **Broken AJAX search** | `new-sales.blade.php` | Changed JS `{ term: query }` → `{ search: query }` to match controller reading `$request->search` |
| **Refund form route** | `refund.blade.php` | Changed form action from `route('sales.store')` → `route('sales.processRefund')` so refunds use the proper method |
| **dd() in production** | `SalesController.php` | Removed `dd($e->getMessage())` — now properly rolls back and redirects with error message |

### 🟡 Medium Issue Fixes

| Fix | File | Change |
|-----|------|--------|
| **Weak passwords** | `UserController.php` | Password field added to form (min 6 chars). If blank, auto-generates `{username}@123`. Validation added for password length. Added `unique:users,username` validation |
| **Password field in form** | `add-user.blade.php` | Added password input field with hint about auto-generation |
| **Ghost fillable fields** | `Category.php` | Removed `price` and `category_id` from `$fillable` — they don't exist in migration |
| **Inactive user check** | `UserController.php` | Added status check in `login()` — disabled accounts (status=0) are rejected with message |
| **Product delete** | `ProductController.php` + routes + view | Added `destroy()` method, `DELETE /product/{id}` route, proper form with confirm dialog |
| **Category delete** | `CategoryController.php` + routes + view | Added `destroy()` method, `DELETE /category/{id}` route, proper form with confirm dialog |
| **User delete** | `UserController.php` + routes + view | Added `destroy()` method (prevents self-deletion), `DELETE /user/{id}` route, proper form with confirm dialog |

---

## 📋 Features That Should Be Added

### 1. Inventory / Stock Management
- Add `stock` or `quantity` column to `product` table
- Show stock level on product list
- Decrease stock when sale is created, increase on refund
- Warn when stock is low

### 2. Database-Level Foreign Key Enforcement
- `sales_product.sale_id` and `sales_product.product_id` are `integer` types with **no foreign key constraints** — should be `foreignId()->constrained()`
- `sales_product` pivot lacks proper FK → CASCADE on delete

### 3. Proper Sale ↔ Refund Linking
- Currently refunds create separate records with no link to original sale
- Add `original_sale_id` nullable FK on `sales` table
- Show refund status on original sale row in listing

### 4. Sales Edit / Void
- Add ability to edit sale details (customer name, payment method) — but NOT products once billed
- Add "Void Sale" option (different from refund — cancels without returning products)

### 5. User Management Enhancements
- Add **edit user** functionality (change name, email, user_type, status)
- Add **status toggle** (active/inactive) without deleting
- Show status badge (active/inactive) in users table

### 6. Granular Role-Based Permissions
- Current: only `admin` (user_type=0) and `user` (user_type=1)
- Add more roles: `manager`, `viewer`, `cashier`
- Restrict access per feature (e.g., viewers can only see reports)

### 7. Email / Notification System
- `.env` has mail config but it's unused
- Send invoice PDF by email on sale creation
- Notify admin on new user registration
- Low stock alerts

### 8. API Endpoints
- REST API for sales, products, categories
- Enable mobile or third-party integration
- API authentication via Laravel Sanctum

### 9. Export Enhancements
- DataTables already handles basic export
- Add server-side report generation (PDF invoice download)
- Scheduled email reports (daily/weekly sales summary)

### 10. Sales Dashboard Charts
- Dashboard has Chart.js included but charts are commented out
- Add monthly sales trend chart (bar or line)
- Add category-wise sales pie chart
- Add top-selling products list

### 11. Activity Log Enhancements
- `ip_address` column exists in logs table but is never written
- Log user-agent, IP address on login and key actions
- Add log filtering (by user, date range, action type)
- Add log retention/purge mechanism

### 12. Multi-Tenancy / Branch Support
- Allow multiple company profiles or branches
- Filter sales/products by branch
- Branch-level reporting

---

## 🧹 Code Quality Improvements

### High Priority

| Improvement | Why |
|-------------|-----|
| **Blade layout inheritance** | Every view duplicates `<html>`, `<head>`, `<body>`, wrapper, sidebar, footer, scripts. Create `layouts/app.blade.php` with `@yield('content')` and `@stack('scripts')` |
| **Extract inline JS** | Move jQuery/DataTables/autocomplete logic from Blade files into organized JS files in `resources/js/` |
| **Service layer** | Move business logic (bill generation, refund processing, pricing calculations) out of controllers into dedicated service classes like `BillingService`, `ReportService` |
| **Form Request classes** | Create dedicated form request classes for validation (e.g., `StoreProductRequest`, `StoreSaleRequest`) instead of inline validation in controllers |
| **Repository pattern** | Extract database queries into repository classes for testability |
| **Route naming consistency** | Normalize to `resource.snake_case` consistently (mix of `sales.store`, `sales.new-sales`, `products.add-product` currently) |

### Medium Priority

| Improvement | Why |
|-------------|-----|
| **Sales model relationships** | Add `products()` (hasManyThrough or belongsToMany), `items()` (hasMany SalesProduct) to Sales model — avoid raw `DB::table()` joins in report controller |
| **Company singleton enforcement** | Either add unique constraint or enforce single-record at model level with `firstOrCreate` pattern |
| **Pagination for large datasets** | DataTables client-side pagination works for small datasets. Add server-side `paginate()` + DataTables server-side processing for 10K+ records |
| **Error/exception handling** | Add global exception handler for common errors, consistent JSON error responses |
| **Unit/Pest tests** | No tests exist. Add at minimum: model relationship tests, controller smoke tests, login/auth tests |
| **Password change validation** | Add new password confirmation field, password strength requirements, prevent reusing last N passwords |

### Nice-to-Have

| Improvement | Why |
|-------------|-----|
| **TailwindCSS usage** | Installed but unused — all styling is AdminLTE. Either remove or use for custom components |
| **Remove unused JS/CSS** | Summernote, Chart.js, Sparkline, jQVMap are loaded on every page but unused on most |
| **Database indexing** | Add indexes on `sales.created_at`, `sales.is_refund`, `sales_product.sale_id` for report performance |
| **Soft deletes** | Use `SoftDeletes` trait on products, categories, users for recoverability |
| **Audit trail improvements** | Store more context (IP address, user agent, old/new values) in logs |
| **CI/CD pipeline** | Add GitHub Actions for running tests on push/PR |
| **Docker setup** | Laravel Sail is in composer but no docker-compose setup documented |

---

## 🧪 Suggested Testing Strategy

Since Pest PHP is already installed:

```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── LoginTest.php          # Login/logout, inactive user rejected
│   │   └── RoleMiddlewareTest.php  # Admin-only route access
│   ├── Sales/
│   │   ├── CreateSaleTest.php      # Store sale with products
│   │   ├── ProcessRefundTest.php   # Refund creation
│   │   ├── BillViewTest.php        # Bill renders correctly
│   │   └── SearchProductTest.php   # AJAX search works
│   ├── Product/
│   │   ├── CreateProductTest.php
│   │   ├── UpdateProductTest.php
│   │   └── DeleteProductTest.php
│   ├── Category/
│   │   ├── CreateCategoryTest.php
│   │   ├── UpdateCategoryTest.php
│   │   └── DeleteCategoryTest.php
│   └── Report/
│       └── SalesReportTest.php     # Date filtering, totals
└── Unit/
    ├── Models/
    │   ├── ProductTest.php          # Relationships
    │   ├── SalesTest.php            # Relationships
    │   └── CategoryTest.php         # HasMany products
    └── PricingTest.php              # Tax/discount calculations
```

---

## 🚀 Getting Started for New Developers

```bash
# 1. Clone & install
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Database (update .env first: DB_DATABASE, DB_USERNAME, DB_PASSWORD)
php artisan migrate
php artisan db:seed

# 4. Dev server
composer run dev

# Default login: admin / password
```

---

## 📊 Current Codebase Metrics

| Metric | Value |
|--------|-------|
| PHP Files (app/) | 15 |
| Blade Templates | 27 |
| Database Migrations | 8 |
| Database Tables | 7 |
| Controllers | 7 |
| Models | 7 |
| Routes | ~30 |
| Tests | 0 |
