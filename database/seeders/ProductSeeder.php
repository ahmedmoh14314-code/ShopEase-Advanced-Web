<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cats = Category::all()->keyBy('slug');
        $subs = Subcategory::all()->keyBy('slug');

        $products = [
            [
                'cat' => 'electronics', 'sub' => 'smart-watches',
                'slug' => 'smart-watch-series-8', 'name' => 'Smart Watch Series 8',
                'price' => 199.99, 'stock' => 45, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?auto=format&fit=crop&w=800&q=80',
                'short' => 'Advanced fitness tracking with always-on display',
                'desc'  => 'The Smart Watch Series 8 features a brilliant always-on Retina display, advanced fitness tracking, blood oxygen sensor, and ECG. Stay connected with calls, messages, and apps right from your wrist.',
            ],
            [
                'cat' => 'electronics', 'sub' => 'headphones',
                'slug' => 'wireless-headphones', 'name' => 'Wireless Headphones Pro',
                'price' => 68.99, 'stock' => 67, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80',
                'short' => 'Premium noise-canceling over-ear headphones',
                'desc'  => 'Immersive sound with industry-leading noise cancellation. 40-hour battery life, dual microphone system, and comfortable memory foam cushions.',
            ],
            [
                'cat' => 'electronics', 'sub' => 'smartphones',
                'slug' => 'iphone-15-pro', 'name' => 'iPhone 15 Pro',
                'price' => 1099.00, 'stock' => 22, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?auto=format&fit=crop&w=800&q=80',
                'short' => 'Titanium design with A17 Pro chip',
                'desc'  => 'The most pro iPhone ever, featuring an aerospace-grade titanium frame, A17 Pro chip, and a customizable Action button. Pro camera system with 5x telephoto zoom.',
            ],
            [
                'cat' => 'electronics', 'sub' => 'smartphones',
                'slug' => 'samsung-galaxy-s24', 'name' => 'Samsung Galaxy S24',
                'price' => 899.00, 'stock' => 38, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?auto=format&fit=crop&w=800&q=80',
                'short' => 'AI-powered flagship smartphone',
                'desc'  => "Galaxy AI is here. Capture, create, and connect like never before with the Galaxy S24's 200MP camera, Snapdragon 8 Gen 3, and a stunning 6.2-inch Dynamic AMOLED 2X display.",
            ],
            [
                'cat' => 'electronics', 'sub' => 'laptops',
                'slug' => 'macbook-air-m3', 'name' => 'MacBook Air M3',
                'price' => 1299.00, 'stock' => 12, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800&q=80',
                'short' => 'Supercharged by the M3 chip',
                'desc'  => 'Strikingly thin and built to take you anywhere, the M3 MacBook Air features an 18-hour battery, up to 24GB unified memory, and a Liquid Retina display.',
            ],
            [
                'cat' => 'fashion', 'sub' => 'men-clothing',
                'slug' => 'denim-jacket', 'name' => 'Classic Denim Jacket',
                'price' => 49.99, 'stock' => 88, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=800&q=80',
                'short' => 'Timeless wardrobe staple',
                'desc'  => 'A classic denim jacket crafted from premium cotton denim. Features button closure, chest pockets, and a regular fit that pairs with anything.',
            ],
            [
                'cat' => 'fashion', 'sub' => 'women-clothing',
                'slug' => 'summer-floral-dress', 'name' => 'Summer Floral Dress',
                'price' => 39.99, 'stock' => 54, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=800&q=80',
                'short' => 'Breezy midi dress for warm days',
                'desc'  => 'Effortless summer style with a flattering A-line silhouette, breathable rayon blend fabric, and a delicate floral print.',
            ],
            [
                'cat' => 'fashion', 'sub' => 'shoes',
                'slug' => 'white-classic-sneakers', 'name' => 'White Classic Sneakers',
                'price' => 89.99, 'stock' => 31, 'featured' => false,
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
                'short' => 'Everyday minimalist sneakers',
                'desc'  => 'Premium leather upper with a soft cushioned insole. Minimalist styling that pairs with everything in your closet.',
            ],
            [
                'cat' => 'fashion', 'sub' => 'bags',
                'slug' => 'leather-shoulder-bag', 'name' => 'Leather Shoulder Bag',
                'price' => 59.99, 'stock' => 26, 'featured' => false,
                'image' => 'https://images.unsplash.com/photo-1591561954557-26941169b49e?auto=format&fit=crop&w=800&q=80',
                'short' => 'Genuine leather everyday bag',
                'desc'  => 'Handcrafted from full-grain leather with brass hardware. Spacious interior with secure zipper compartments.',
            ],
            [
                'cat' => 'beauty', 'sub' => 'perfumes',
                'slug' => 'luxury-perfume', 'name' => 'Luxury Perfume Eau de Parfum',
                'price' => 54.99, 'stock' => 73, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=800&q=80',
                'short' => 'Long-lasting floral fragrance',
                'desc'  => 'An elegant fragrance featuring notes of jasmine, rose, and amber. Hand-finished glass bottle with brushed gold detailing.',
            ],
            [
                'cat' => 'beauty', 'sub' => 'makeup',
                'slug' => 'makeup-essentials-set', 'name' => 'Makeup Essentials Set',
                'price' => 44.99, 'stock' => 42, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1522335789203-aaa413e6065c?auto=format&fit=crop&w=800&q=80',
                'short' => '12-piece curated makeup kit',
                'desc'  => 'Everything you need for a complete everyday look. Includes foundation, blush, eyeshadow palette, lipsticks, and premium brushes.',
            ],
            [
                'cat' => 'beauty', 'sub' => 'skincare',
                'slug' => 'hydrating-face-cream', 'name' => 'Hydrating Face Cream',
                'price' => 24.99, 'stock' => 91, 'featured' => false,
                'image' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=800&q=80',
                'short' => '24-hour deep moisture',
                'desc'  => 'Lightweight formula with hyaluronic acid and ceramides. Deeply moisturizes without feeling greasy. Suitable for all skin types.',
            ],
            [
                'cat' => 'home', 'sub' => 'furniture',
                'slug' => 'minimalist-sofa', 'name' => 'Minimalist 3-Seater Sofa',
                'price' => 399.99, 'stock' => 8, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80',
                'short' => 'Contemporary living room centerpiece',
                'desc'  => 'Solid wood frame with premium fabric upholstery. High-density foam cushions provide lasting comfort. Available in multiple colors.',
            ],
            [
                'cat' => 'home', 'sub' => 'kitchen',
                'slug' => 'coffee-maker', 'name' => 'Espresso Coffee Maker',
                'price' => 59.99, 'stock' => 49, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?auto=format&fit=crop&w=800&q=80',
                'short' => 'Barista-quality coffee at home',
                'desc'  => 'A 15-bar pressure pump, milk frother, and removable water tank. Brews espresso, cappuccino, and latte with a touch.',
            ],
            [
                'cat' => 'home', 'sub' => 'kitchen',
                'slug' => 'robot-vacuum', 'name' => 'Smart Robot Vacuum',
                'price' => 249.00, 'stock' => 34, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1567690187548-f07b1d7bf5a9?auto=format&fit=crop&w=800&q=80',
                'short' => 'Hands-free home cleaning',
                'desc'  => 'Smart laser mapping, 2700Pa suction, 120-minute runtime. App-controlled with scheduled cleaning and zone mapping.',
            ],
            [
                'cat' => 'sports', 'sub' => 'fitness',
                'slug' => 'adjustable-dumbbell-set', 'name' => 'Adjustable Dumbbell Set',
                'price' => 119.99, 'stock' => 18, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=800&q=80',
                'short' => '5-50 lb in one compact unit',
                'desc'  => 'Quick-adjust dial replaces 15 sets of weights. Durable steel plates with non-slip grip. Includes storage tray.',
            ],
            [
                'cat' => 'sports', 'sub' => 'sports-shoes',
                'slug' => 'running-shoes-flex', 'name' => 'Running Shoes Flex',
                'price' => 84.99, 'stock' => 64, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
                'short' => 'Cushioned everyday runners',
                'desc'  => 'Engineered mesh upper for breathability, responsive foam midsole, and a durable rubber outsole. Built for daily miles.',
            ],
            [
                'cat' => 'toys', 'sub' => 'plush-toys',
                'slug' => 'plush-teddy-bear', 'name' => 'Plush Teddy Bear',
                'price' => 24.99, 'stock' => 122, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&w=800&q=80',
                'short' => 'Ultra-soft 18-inch cuddle companion',
                'desc'  => 'Made from premium hypoallergenic plush with embroidered features for safety. Surface washable. A timeless gift.',
            ],
            [
                'cat' => 'books', 'sub' => 'programming',
                'slug' => 'laravel-practical-guide', 'name' => 'Laravel Practical Guide',
                'price' => 39.99, 'stock' => 45, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=800&q=80',
                'short' => 'Build modern web apps with Laravel',
                'desc'  => 'A hands-on, project-driven book that takes you from beginner to advanced Laravel developer. Covers Eloquent, Sanctum, queues, and testing.',
            ],
            [
                'cat' => 'books', 'sub' => 'self-development',
                'slug' => 'atomic-habits-journal', 'name' => 'Atomic Habits Style Journal',
                'price' => 19.99, 'stock' => 87, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?auto=format&fit=crop&w=800&q=80',
                'short' => 'Habit tracker + reflection journal',
                'desc'  => '180-day undated journal designed for habit stacking, weekly reviews, and intentional living. Premium hardcover with ribbon bookmarks.',
            ],
            [
                'cat' => 'accessories', 'sub' => 'backpacks',
                'slug' => 'urban-travel-backpack', 'name' => 'Urban Travel Backpack',
                'price' => 44.99, 'stock' => 56, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80',
                'short' => '30L weatherproof commuter pack',
                'desc'  => 'Padded laptop sleeve fits 16 inch, water-resistant exterior, organized compartments, and luggage strap for travel.',
            ],
            [
                'cat' => 'accessories', 'sub' => 'watches',
                'slug' => 'classic-leather-watch', 'name' => 'Classic Leather Watch',
                'price' => 79.99, 'stock' => 41, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80',
                'short' => 'Minimalist timepiece, premium feel',
                'desc'  => 'Sapphire glass face, genuine Italian leather strap, Japanese quartz movement, and a brushed steel case in 40mm.',
            ],
            [
                'cat' => 'auto', 'sub' => 'wheels',
                'slug' => 'sport-alloy-wheel', 'name' => 'Sport Alloy Wheel 18"',
                'price' => 159.99, 'stock' => 24, 'featured' => true,
                'image' => 'https://images.unsplash.com/photo-1611821064430-0d40291922d2?auto=format&fit=crop&w=800&q=80',
                'short' => 'Lightweight performance wheel',
                'desc'  => 'Forged aluminum construction reduces unsprung weight. Matte gunmetal finish. Sold per wheel - TPMS sensor not included.',
            ],
        ];

        foreach ($products as $p) {
            $cat = $cats[$p['cat']] ?? null;
            $sub = $subs[$p['sub']] ?? null;

            if (! $cat) {
                continue;
            }

            Product::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'category_id'       => $cat->id,
                    'subcategory_id'    => $sub?->id,
                    'name'              => $p['name'],
                    'short_description' => $p['short'],
                    'description'       => $p['desc'],
                    'price'             => $p['price'],
                    'stock'             => $p['stock'],
                    'image'             => $p['image'],
                    'is_featured'       => $p['featured'],
                    'is_active'         => true,
                ]
            );
        }
    }
}
