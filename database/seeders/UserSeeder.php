<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\RandomUserService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = RandomUserService::fetch(15);

        foreach ($users as $data) {
            $name = trim(($data['name']['first'] ?? '').' '.($data['name']['last'] ?? ''));
            $email = $data['email'] ?? null;

            if (! $email || User::where('email', $email)->exists()) {
                $email = 'user_'.uniqid().'@puppiary.test';
            }

            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name ?: fake()->name(),
                    'password' => Hash::make('password'),
                    'phone' => preg_replace('/[^0-9+]/', '', $data['phone'] ?? '') ?: null,
                ]
            );
        }

        // Ensure test user exists
        $testUser = [
            'name' => 'Test User',
            'password' => Hash::make('password'),
        ];
        if (Schema::hasColumn('users', 'phone')) {
            $testUser['phone'] = '+2348090000001';
        }
        if (Schema::hasColumn('users', 'notify_order_updates')) {
            $testUser['notify_order_updates'] = true;
            $testUser['notify_marketing'] = false;
        }
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            $testUser
        );
    }
}
