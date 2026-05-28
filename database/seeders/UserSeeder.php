<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'      => 'Admin User',
                'email'     => 'admin@shopease.com',
                'password'  => 'password123',
                'role'      => 'admin',
                'phone'     => '+90 555 100 1001',
                'is_active' => true,
            ],
            [
                'name'      => 'Store Manager',
                'email'     => 'manager@shopease.com',
                'password'  => 'password123',
                'role'      => 'manager',
                'phone'     => '+90 555 100 1002',
                'is_active' => true,
            ],
            [
                'name'      => 'Demo Customer',
                'email'     => 'customer@shopease.com',
                'password'  => 'password123',
                'role'      => 'customer',
                'phone'     => '+90 555 100 1003',
                'is_active' => true,
            ],
            [
                'name'      => 'Sarah Johnson',
                'email'     => 'sarah@example.com',
                'password'  => 'password123',
                'role'      => 'customer',
                'phone'     => '+90 555 200 2001',
                'is_active' => true,
            ],
            [
                'name'      => 'Michael Brown',
                'email'     => 'michael@example.com',
                'password'  => 'password123',
                'role'      => 'customer',
                'phone'     => '+90 555 200 2002',
                'is_active' => true,
            ],
            [
                'name'      => 'Emily Wilson',
                'email'     => 'emily@example.com',
                'password'  => 'password123',
                'role'      => 'customer',
                'phone'     => '+90 555 200 2003',
                'is_active' => true,
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }
    }
}
