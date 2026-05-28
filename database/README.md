# Database Seeders

This folder contains the Laravel seeders that populate the database with realistic demo data for the ShopEase project.

## How to apply

After running the migrations, install the demo data with:

```
php artisan migrate --seed
```

Or, to re-seed an existing database without dropping tables:

```
php artisan db:seed
```

`DatabaseSeeder` automatically truncates the catalog/order/cart/message/settings tables before seeding so the data stays consistent. **It does not truncate the `users` table** — demo accounts are kept in place via `updateOrCreate`.

## What gets seeded

| Seeder                  | Records                                                   |
|-------------------------|-----------------------------------------------------------|
| `UserSeeder`            | 6 users (1 admin, 1 manager, 4 customers)                 |
| `CategorySeeder`        | 9 product categories                                      |
| `SubcategorySeeder`     | 22 subcategories (21 active, 1 inactive)                  |
| `ProductSeeder`         | 23 products with images, descriptions, stock, featured flag |
| `OrderSeeder`           | 8 orders (mix of pending / processing / shipped / delivered / cancelled) |
| `CartSeeder`            | 3-item demo cart for the customer demo account            |
| `ContactMessageSeeder`  | 6 contact messages (mix of read / unread)                 |
| `SettingSeeder`         | 5 site settings (name, email, phone, address, currency)   |

## Demo accounts

| Role     | Email                  | Password    |
|----------|------------------------|-------------|
| Admin    | admin@shopease.com     | password123 |
| Manager  | manager@shopease.com   | password123 |
| Customer | customer@shopease.com  | password123 |

## Product images

All product, category, and subcategory images are external URLs hosted on `images.unsplash.com`. The frontend's `assetUrl()` helper passes external URLs through unchanged, so the images render in both the storefront and the admin panel without needing `php artisan storage:link` to host them locally.
