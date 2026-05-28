<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->cleanupCatalogTables();

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SubcategorySeeder::class,
            ProductSeeder::class,
            SettingSeeder::class,
            ContactMessageSeeder::class,
            OrderSeeder::class,
            CartSeeder::class,
        ]);
    }

    private function cleanupCatalogTables(): void
    {
        $tables = [
            'order_items',
            'orders',
            'cart_items',
            'carts',
            'products',
            'subcategories',
            'categories',
            'contact_messages',
            'settings',
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
