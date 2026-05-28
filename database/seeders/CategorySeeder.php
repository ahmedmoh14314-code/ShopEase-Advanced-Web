<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'  => 'Electronics',
                'slug'  => 'electronics',
                'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'  => 'Fashion',
                'slug'  => 'fashion',
                'image' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'  => 'Home & Living',
                'slug'  => 'home',
                'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'  => 'Beauty',
                'slug'  => 'beauty',
                'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'  => 'Sports',
                'slug'  => 'sports',
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'  => 'Toys & Games',
                'slug'  => 'toys',
                'image' => 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'  => 'Books',
                'slug'  => 'books',
                'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'  => 'Automotive',
                'slug'  => 'auto',
                'image' => 'https://images.unsplash.com/photo-1611821064430-0d40291922d2?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'  => 'Accessories',
                'slug'  => 'accessories',
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($categories as $c) {
            Category::updateOrCreate(
                ['slug' => $c['slug']],
                [
                    'name'      => $c['name'],
                    'image'     => $c['image'],
                    'is_active' => true,
                ]
            );
        }
    }
}
