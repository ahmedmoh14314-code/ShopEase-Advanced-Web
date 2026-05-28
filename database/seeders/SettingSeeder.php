<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name'    => 'ShopEase',
            'site_email'   => 'support@shopease.com',
            'site_phone'   => '+90 555 000 0000',
            'site_address' => 'Istanbul, Turkey',
            'currency'     => 'USD',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
