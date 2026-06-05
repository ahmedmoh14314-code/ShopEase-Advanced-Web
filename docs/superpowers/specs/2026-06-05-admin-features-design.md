# Design Spec — New Admin Backend Features (Reviews, FAQ, Informational Pages)

**Date:** 2026-06-05
**Scope:** Laravel backend (API) only. No frontend work.
**Goal:** Add the admin-panel feature areas shown in the reference screenshots that are missing
from the current backend: product **Reviews** (public + moderation), **FAQ** management, and
**Informational** static pages.

## Constraints

- Do not change existing project structure or break existing features.
- Do not touch secrets / `.env`.
- Order statuses stay as-is (`pending, processing, shipped, delivered, cancelled`) — out of scope.
- Follow the existing CRUD pattern. In this repo validation lives in **Form Request** classes
  (`App\Http\Requests\...`), so admin/customer create and update use dedicated `Store*`/`Update*`
  requests returning JSON `{ message, resource }`. Keep the existing folder layout
  (`Api/Public`, `Api/Customer`, `Api/Admin`) and the existing role middleware.
- Commits are local only (not pushed) and must not include any AI co-author attribution.

## Approach

Three independent feature modules, each following the existing patterns. Rejected alternatives:
merging FAQ + Pages into one generic "content" model (less clear, weaker validation), and an
admin-only build with no public endpoints (the user wants reviews and FAQ/pages publicly readable).

---

## 1. Reviews (public submission + admin moderation)

**Model `App\Models\Review`**
- `product_id` (FK → products, cascade on delete)
- `user_id` (FK → users, cascade on delete)
- `rating` (unsigned tinyint, 1–5)
- `comment` (text, nullable)
- `is_approved` (boolean, default `false`)
- timestamps
- Unique constraint `(product_id, user_id)` → one review per customer per product.

**Relationships**
- `Product hasMany Review` (`reviews`)
- `User hasMany Review` (`reviews`)

**Endpoints**
- Public: `GET /api/products/{slug}/reviews` → approved reviews for the product, paginated (10),
  newest first, each with reviewer name (`user.name`).
- Customer (`auth:sanctum`): `POST /api/products/{slug}/reviews`
  - Validation: `rating` required integer 1–5; `comment` nullable string max 2000.
  - Created with `is_approved = false` (pending moderation).
  - Rejects a second review by the same user for the same product (422).
- Admin (`role:admin,manager`):
  - `GET /api/admin/reviews` → all reviews, paginated (15), filterable by `status`
    (`approved` / `pending`) and `product_id`, eager-loads `user` and `product`.
  - `PUT /api/admin/reviews/{review}/approve` → sets `is_approved = true`.
  - `DELETE /api/admin/reviews/{review}` → deletes the review.

**Product enrichment**
- `Api/Public/ProductController@show` adds `reviews_avg_rating` (rounded to 1 decimal, approved
  only) and `reviews_count` (approved only) to the product payload. Implemented via
  `withAvg`/`withCount` constrained to approved reviews so existing response shape is only extended,
  not changed.

**Controllers**
- `Api/ReviewController` → `index($slug)`, `store(Request, $slug)`
- `Api/Admin/ReviewController` → `index`, `approve`, `destroy`

**Seeder `ReviewSeeder`** — a handful of reviews across several products for the demo customers,
mix of approved and pending so the moderation queue is non-empty.

---

## 2. FAQ (admin CRUD + public list)

**Model `App\Models\Faq`**
- `question` (string)
- `answer` (text)
- `sort_order` (integer, default 0)
- `is_active` (boolean, default `true`)
- timestamps

**Endpoints**
- Public: `GET /api/faqs` → active FAQs ordered by `sort_order` then `id`.
- Admin (`role:admin,manager`): `apiResource /api/admin/faqs` (index, store, show, update, destroy).
  - Validation: `question` required string max 255; `answer` required string; `sort_order`
    nullable integer min 0; `is_active` boolean (defaults true on create).

**Controllers**
- `Api/FaqController` → `index`
- `Api/Admin/FaqController` → full resource

**Seeder `FaqSeeder`** — ~6 realistic store FAQs (shipping, payment, returns, account, etc.).

---

## 3. Informational static pages (admin-only CRUD + public read)

**Model `App\Models\InfoPage`** (table `info_pages`)
- `title` (string)
- `slug` (string, unique)
- `content` (longtext)
- `is_published` (boolean, default `true`)
- timestamps

**Endpoints**
- Public: `GET /api/pages` → published pages (`title`, `slug` only); `GET /api/pages/{slug}` →
  one published page (404 if not found / unpublished).
- Admin (`role:admin` only): `apiResource /api/admin/pages` (index, store, show, update, destroy).
  - Validation: `title` required string max 255; `slug` required string max 255 unique
    (ignoring self on update); `content` required string; `is_published` boolean (defaults true).

**Controllers**
- `Api/InfoPageController` → `index`, `show`
- `Api/Admin/InfoPageController` → full resource

**Seeder `InfoPageSeeder`** — About Us, Privacy Policy, Terms of Service, Shipping & Returns.

---

## Cross-cutting changes

- **Migrations** numbered to slot into the existing convention:
  - `2024_01_01_000011_create_faqs_table`
  - `2024_01_01_000012_create_info_pages_table`
  - `2024_01_01_000013_create_reviews_table` (after products/users)
- **`DatabaseSeeder`**: register `ReviewSeeder`, `FaqSeeder`, `InfoPageSeeder`; add `reviews`,
  `faqs`, `info_pages` to the truncate cleanup list (reviews before products in FK order).
- **Dashboard** (`Api/Admin/DashboardController@index`): add `pending_reviews_count`,
  `faqs_count`, `info_pages_count` to the totals payload (additive only).
- **Routes** (`routes/api.php`): add the public, customer, and admin route entries above, grouped
  with the existing public / `auth:sanctum` / `role:*` blocks. Route imports added at top.

## Permissions summary

| Area      | Public read | Customer write | admin+manager | admin only |
|-----------|-------------|----------------|---------------|------------|
| Reviews   | approved    | submit         | moderate      | —          |
| FAQ       | active      | —              | CRUD          | —          |
| InfoPages | published   | —              | —             | CRUD       |

## Testing

Feature tests (PHPUnit, `RefreshDatabase`) covering the main paths:
- Reviews: public sees only approved; customer submit creates pending; duplicate submit rejected;
  admin approve flips `is_approved`; admin delete removes it.
- FAQ: public sees only active ordered; admin CRUD happy paths; manager allowed, customer forbidden.
- InfoPages: public sees only published; admin CRUD; manager forbidden on pages; customer forbidden.

Note: `vendor/` is not installed in this checkout (git-ignored), so running the suite requires
`composer install` first. Tests will be written regardless.

## Out of scope

- Any frontend / React work.
- Changing order statuses.
- Editing reviews by admin (approve + delete only; no edit of customer content).
- Rich-text/markdown rendering of page content (stored and returned as-is).
