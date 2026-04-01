<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            ShieldSeeder::class,
            DeliveryZoneSeeder::class,
            SettingSeeder::class,
            DiscountCodeSeeder::class,
            EmailTemplateSeeder::class,
            ProductSeeder::class,
            ProductBundleSeeder::class,
            ArticleSeeder::class,
            QuizSeeder::class,
            OrderSeeder::class,
            PuppySeeder::class,
            ReviewSeeder::class,
            CustomerDashboardSeeder::class,
            PolicySeeder::class,
        ]);
    }
}
