# ShopEase

A server-rendered e-commerce application built with Laravel and Blade. Customers browse a
catalogue, search and filter it, add products to a cart, and place a cash-on-delivery order.
Staff sign in to a role-restricted admin panel to manage categories, products, and the orders
that come in.

There is no public API — every page is rendered server-side from Blade templates.

## Features

**Storefront**

- Home page with active categories and up to eight featured products
- Product listing with search by name, category filter, min/max price filter, and pagination
- Product detail page with related products from the same category; inactive products return 404

**Accounts**

- Registration, login and logout using Laravel's session authentication
- Three roles: `customer`, `manager`, `admin`

**Cart and checkout**

- A cart per signed-in user, stored in the database, with add / change quantity / remove
- Checkout captures a shipping address and creates the order inside a database transaction:
  order items are written with the price at time of purchase, product stock is decremented,
  and the cart is emptied
- Payment method is fixed to cash on delivery; there is no payment gateway

**Customer orders**

- "My orders" list and order detail, with an ownership check so one customer cannot open
  another customer's order

**Admin panel** (`/admin`, restricted to `admin` and `manager`)

- Dashboard
- Category CRUD and product CRUD, including subcategory assignment
- Order list and detail, and a status action: approve → `processing`, reject → `cancelled`,
  completed → `delivered`

**Tests**

14 feature tests covering the storefront, cart and checkout, admin CRUD, order status
transitions, and the authorization boundaries (guests redirected to login, customers blocked
from the admin panel).

## Tech stack

| | |
|---|---|
| PHP 8.3, Laravel 13 | Application framework |
| Blade | Server-rendered views |
| MySQL 8 | Database, via Eloquent migrations, factories and seeders |
| Tailwind CSS 4 | Styling, compiled through Vite |
| Vite | Asset bundling (`laravel-vite-plugin`) |
| PHPUnit | Feature and unit tests |
| Laravel Sanctum | Installed with the skeleton; not used by any route in this project |

## Structure

```
app/
├── Http/Controllers/Web/        storefront, auth, cart, checkout, orders
│   └── Admin/                   dashboard, categories, products, orders
├── Http/Middleware/
│   └── RoleMiddleware.php       the role:admin,manager gate
└── Models/                      User, Category, Subcategory, Product,
                                 Cart, CartItem, Order, OrderItem, ...
database/
├── migrations/                  14 tables
├── factories/  seeders/         demo catalogue, users and orders
resources/views/                 Blade templates: layouts, storefront, admin
routes/web.php                   every route in the application
tests/Feature/                   storefront, cart/checkout, admin
```

Routes are grouped by who may reach them: public, `guest`, `auth`, and
`auth + role:admin,manager`. The role check lives in one middleware rather than being repeated
in each controller.

Several models exist from the original schema — `Review`, `Faq`, `InfoPage`, `ContactMessage`,
`Setting` — with migrations and seeders but no controller or view. They are scaffolding, not
working features.

## Running locally

Requires PHP 8.3+, Composer, Node 18+, and MySQL.

```bash
git clone https://github.com/ahmedmoh14314-code/ShopEase-Advanced-Web.git
cd ShopEase-Advanced-Web

composer install
cp .env.example .env
php artisan key:generate
```

Create a database named `shopease` and update the `DB_*` values in `.env`, then:

```bash
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

The app runs at `http://localhost:8000`. Seeded accounts, all with the password
`password123`:

| Email | Role |
|---|---|
| `admin@shopease.com` | admin |
| `manager@shopease.com` | manager |
| `customer@shopease.com` | customer |

Run the tests with `php artisan test`.

## Academic context

Built as a university project for an Advanced Web Programming course. It is coursework, not a
deployed commercial system: there is no live site, no payment processing, and no production
hardening.

---

**Ahmed Abouelmagd**
