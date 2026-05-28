# ShopEase Laravel React E-commerce Project

**Student Name:** Ahmed Mohamed Ahmed Abdelfattah Aboulemagd

**Student Number:** 20232022064

**Course:** Advanced Web Programming

---

## Project Description

ShopEase is a full-stack e-commerce application with a public storefront and a role-based admin panel. The backend is built with Laravel 13 and exposes a REST API secured with Laravel Sanctum. The frontend is a single-page React 18 application built with Vite and styled with plain CSS.

The project supports browsing categories and products, a customer cart and checkout flow with cash-on-delivery payment, contact messages, and an admin dashboard for managing the catalog, orders, users, messages, and site settings.

## Main Features

**Public storefront**

- Home page with featured products and category showcase
- Product listing with search, category filter, subcategory filter, price range, and "featured only" toggle
- Product details page with related products
- Customer registration and login
- Cart with quantity updates and item removal
- Checkout with cash-on-delivery
- My Orders list and order details
- Contact form
- Account dropdown menu in the header
- Styled logout confirmation dialog

**Admin panel**

- Dashboard with totals, latest orders, latest messages, and revenue from delivered orders
- Categories CRUD with image upload
- Subcategories CRUD linked to categories
- Products CRUD with image upload and stock control
- Orders list with inline status updates
- Users management (admin only)
- Contact messages with read/unread state (admin only)
- Site settings: name, support email, phone, address, currency (admin only)

**Roles**

- `customer` — storefront only
- `manager` — admin access except users, messages, settings
- `admin` — full admin access

## Tech Stack

- **Backend:** PHP 8.x, Laravel 13, Sanctum, MySQL/MariaDB
- **Frontend:** React 18, Vite, React Router 7, Axios, plain CSS
- **Auth:** Bearer tokens via Laravel Sanctum
- **Build:** Vite for frontend production bundle

## Folder Structure

```
ShopEase-Laravel-React/
  backend/         Laravel API (routes, controllers, models, migrations, seeders)
  frontend/        React SPA (pages, layouts, components, services, styles)
  README.md
  .gitignore
```

## Backend Setup

```
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Backend runs on `http://127.0.0.1:8000` by default. The API is served under `/api`.

In `backend/.env`, configure your local database connection (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

## Frontend Setup

```
cd frontend
npm install
cp .env.example .env
npm run dev
```

Frontend dev server runs on `http://127.0.0.1:5173` by default.

`frontend/.env`:

```
VITE_API_URL=http://127.0.0.1:8000/api
```

To build for production:

```
npm run build
```

## Database Setup

Migrations and seeders ship with the project. After configuring `backend/.env`, run:

```
php artisan migrate --seed
```

If you want to re-seed without resetting the database, you can run individual seeders, for example:

```
php artisan db:seed --class=ProductSeeder
```

This project includes realistic demo data through Laravel seeders:

- categories
- subcategories
- products
- product images (external URLs)
- orders
- cart demo for the demo customer
- contact messages
- settings
- demo users (admin, manager, customer, and a few extra customers)

See `backend/database/README.md` for details about the seeders.

## Demo Accounts

| Role     | Email                     | Password    |
|----------|---------------------------|-------------|
| Admin    | admin@shopease.com        | password123 |
| Manager  | manager@shopease.com      | password123 |
| Customer | customer@shopease.com     | password123 |

The customer account has a pre-seeded cart so the cart page looks populated on first visit.

## GitHub Review Note

This repository contains the source code only. The following are intentionally excluded via `.gitignore`:

- `node_modules/`, `vendor/` — install with `npm install` / `composer install`
- `.env` files — copy from `.env.example` and adjust local values
- `frontend/dist/` — produced by `npm run build`
- `backend/storage/logs/`, `backend/bootstrap/cache/` — runtime artifacts

After cloning, follow the **Backend Setup** and **Frontend Setup** steps above, then run `php artisan migrate --seed` once to populate the database with the demo data.
