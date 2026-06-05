<?php

namespace Database\Seeders;

use App\Models\InfoPage;
use Illuminate\Database\Seeder;

class InfoPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title'        => 'About Us',
                'slug'         => 'about-us',
                'content'      => "ShopEase is a demo e-commerce store built to showcase a full online shopping experience: browsing a catalog, managing a cart, and placing orders with cash on delivery. Our goal is to keep shopping simple, fast, and friendly.",
                'is_published' => true,
            ],
            [
                'title'        => 'Privacy Policy',
                'slug'         => 'privacy-policy',
                'content'      => "We respect your privacy. The information you provide (name, email, phone, and shipping address) is used only to process and deliver your orders and to contact you about them. We do not sell your personal data to third parties.",
                'is_published' => true,
            ],
            [
                'title'        => 'Terms of Service',
                'slug'         => 'terms-of-service',
                'content'      => "By using ShopEase you agree to provide accurate account and shipping information, to use the store for lawful purposes only, and to honor orders placed under the cash-on-delivery option. Prices and product availability may change without notice.",
                'is_published' => true,
            ],
            [
                'title'        => 'Shipping & Returns',
                'slug'         => 'shipping-and-returns',
                'content'      => "Orders are typically delivered within 2-5 business days. You may request a return within 14 days of delivery for unused items in their original packaging. Refunds are issued once the returned item is received and inspected.",
                'is_published' => true,
            ],
        ];

        foreach ($pages as $page) {
            InfoPage::create($page);
        }
    }
}
