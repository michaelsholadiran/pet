<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'payment', 'key' => 'gateway', 'value' => 'stripe'],
            ['group' => 'tax', 'key' => 'rate', 'value' => '0'],
            ['group' => 'branding', 'key' => 'logo_url', 'value' => ''],
            ['group' => 'branding', 'key' => 'primary_color', 'value' => '#f59e0b'],
        ];

        foreach ($settings as $data) {
            Setting::updateOrCreate(
                ['group' => $data['group'], 'key' => $data['key']],
                ['value' => $data['value'] ?? '']
            );
        }
    }
}
