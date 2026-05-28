<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        $cats = Category::all()->keyBy('slug');

        $subcategories = [
            // electronics
            ['category' => 'electronics', 'name' => 'Smart Watches',    'slug' => 'smart-watches',    'active' => true],
            ['category' => 'electronics', 'name' => 'Headphones',       'slug' => 'headphones',       'active' => true],
            ['category' => 'electronics', 'name' => 'Smartphones',      'slug' => 'smartphones',      'active' => true],
            ['category' => 'electronics', 'name' => 'Laptops',          'slug' => 'laptops',          'active' => true],
            ['category' => 'electronics', 'name' => 'Tablets',          'slug' => 'tablets',          'active' => true],

            // fashion
            ['category' => 'fashion',     'name' => 'Men Clothing',     'slug' => 'men-clothing',     'active' => true],
            ['category' => 'fashion',     'name' => 'Women Clothing',   'slug' => 'women-clothing',   'active' => true],
            ['category' => 'fashion',     'name' => 'Shoes',            'slug' => 'shoes',            'active' => true],
            ['category' => 'fashion',     'name' => 'Bags',             'slug' => 'bags',             'active' => true],

            // home & living
            ['category' => 'home',        'name' => 'Furniture',        'slug' => 'furniture',        'active' => true],
            ['category' => 'home',        'name' => 'Kitchen',          'slug' => 'kitchen',          'active' => true],

            // beauty
            ['category' => 'beauty',      'name' => 'Perfumes',         'slug' => 'perfumes',         'active' => true],
            ['category' => 'beauty',      'name' => 'Makeup',           'slug' => 'makeup',           'active' => true],
            ['category' => 'beauty',      'name' => 'Skincare',         'slug' => 'skincare',         'active' => true],

            // sports
            ['category' => 'sports',      'name' => 'Fitness',          'slug' => 'fitness',          'active' => true],
            ['category' => 'sports',      'name' => 'Sports Shoes',     'slug' => 'sports-shoes',     'active' => true],

            // toys & games
            ['category' => 'toys',        'name' => 'Plush Toys',       'slug' => 'plush-toys',       'active' => true],

            // books
            ['category' => 'books',       'name' => 'Programming',      'slug' => 'programming',      'active' => true],
            ['category' => 'books',       'name' => 'Self Development', 'slug' => 'self-development', 'active' => true],

            // automotive
            ['category' => 'auto',        'name' => 'Wheels',           'slug' => 'wheels',           'active' => true],

            // accessories
            ['category' => 'accessories', 'name' => 'Backpacks',        'slug' => 'backpacks',        'active' => true],
            ['category' => 'accessories', 'name' => 'Watches',          'slug' => 'watches',          'active' => false],
        ];

        foreach ($subcategories as $s) {
            $cat = $cats[$s['category']] ?? null;
            if (! $cat) {
                continue;
            }

            Subcategory::updateOrCreate(
                ['slug' => $s['slug']],
                [
                    'category_id' => $cat->id,
                    'name'        => $s['name'],
                    'is_active'   => $s['active'],
                ]
            );
        }
    }
}
