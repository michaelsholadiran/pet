<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web'],
            ['name' => 'super_admin', 'guard_name' => 'web']
        );

        Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'admin', 'guard_name' => 'web']
        );

        Role::firstOrCreate(
            ['name' => 'support', 'guard_name' => 'web'],
            ['name' => 'support', 'guard_name' => 'web']
        );

        Role::firstOrCreate(
            ['name' => 'content_manager', 'guard_name' => 'web'],
            ['name' => 'content_manager', 'guard_name' => 'web']
        );

        // Filament admin: super_admin = full access (see config/filament-shield.php)
        $email = env('FILAMENT_ADMIN_EMAIL', 'admin@gmail.com');
        $plainPassword = env('FILAMENT_ADMIN_PASSWORD', 'password');

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('FILAMENT_ADMIN_NAME', 'Super Admin'),
                'password' => Hash::make($plainPassword),
            ]
        );

        $admin->syncRoles(['super_admin']);

        // Migrate legacy role column if it exists
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'role')) {
            foreach (User::whereIn('role', ['admin', 'support', 'content_manager'])->get() as $user) {
                if (! $user->hasRole('super_admin')) {
                    $user->assignRole($user->role);
                }
            }
        }
    }
}
