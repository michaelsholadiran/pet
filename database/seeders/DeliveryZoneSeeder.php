<?php

namespace Database\Seeders;

use App\Models\DeliveryZone;
use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class DeliveryZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'name' => 'Nigeria (Standard)',
                'countries' => ['NG'],
                'regions' => null,
                'sort_order' => 0,
                'rates' => [
                    ['name' => 'Standard Delivery', 'min_order_amount' => 0, 'rate' => 4800, 'estimated_days_min' => 1, 'estimated_days_max' => 4],
                    ['name' => 'Free Delivery (₦15,000+)', 'min_order_amount' => 1500000, 'rate' => 0, 'estimated_days_min' => 2, 'estimated_days_max' => 5],
                ],
            ],
            [
                'name' => 'Lagos Metro',
                'countries' => ['NG'],
                'regions' => 'Lagos',
                'sort_order' => 1,
                'rates' => [
                    ['name' => 'Same Day (Lagos)', 'min_order_amount' => 0, 'rate' => 8000, 'estimated_days_min' => 0, 'estimated_days_max' => 1],
                ],
            ],
        ];

        foreach ($zones as $zoneData) {
            $rates = $zoneData['rates'];
            unset($zoneData['rates']);

            $zone = DeliveryZone::firstOrCreate(
                ['name' => $zoneData['name']],
                $zoneData
            );

            foreach ($rates as $rateData) {
                ShippingRate::firstOrCreate(
                    [
                        'delivery_zone_id' => $zone->id,
                        'name' => $rateData['name'],
                    ],
                    array_merge($rateData, ['is_active' => true])
                );
            }
        }
    }
}
