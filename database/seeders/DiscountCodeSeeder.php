<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use Illuminate\Database\Seeder;

class DiscountCodeSeeder extends Seeder
{
    public function run(): void
    {
        $codes = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 10000,
                'max_uses' => 1000,
                'first_purchase_only' => true,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
            ],
            [
                'code' => 'SAVE500',
                'type' => 'fixed',
                'value' => 50000, // 500 NGN in kobo
                'min_order_amount' => 15000,
                'max_uses' => null,
                'first_purchase_only' => false,
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
            ],
            [
                'code' => 'PUPPY15',
                'type' => 'percentage',
                'value' => 15,
                'min_order_amount' => 25000,
                'max_uses' => 100,
                'first_purchase_only' => false,
                'starts_at' => null,
                'expires_at' => null,
            ],
        ];

        foreach ($codes as $data) {
            DiscountCode::firstOrCreate(
                ['code' => $data['code']],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
