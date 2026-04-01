<?php

namespace Database\Seeders;

use App\Models\Puppy;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PuppySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->warn('Run UserSeeder first.');

            return;
        }

        $breeds = ['Golden Retriever', 'Labrador', 'German Shepherd', 'French Bulldog', 'Poodle', 'Beagle', 'Yorkshire Terrier', 'Bulldog', 'Chihuahua', 'Maltese'];
        $sizes = ['small', 'medium', 'large'];
        $healthNotesPool = [
            null,
            'No known allergies.',
            'Chicken sensitivity — avoid poultry-based treats.',
            'Vet recommended joint supplement from 12 months.',
        ];

        $take = min(10, $users->count());
        $selectedUsers = $take > 0 ? $users->random($take) : collect();
        foreach ($selectedUsers as $user) {
            $count = rand(1, 2);
            for ($i = 0; $i < $count; $i++) {
                if (Puppy::where('user_id', $user->id)->count() >= 2) {
                    break;
                }

                $row = [
                    'user_id' => $user->id,
                    'name' => fake()->firstName().' (pup)',
                    'breed' => $breeds[array_rand($breeds)],
                    'birth_date' => now()->subWeeks(rand(8, 52)),
                    'weight' => round(rand(2, 25) / 10, 1),
                    'size_category' => $sizes[array_rand($sizes)],
                ];
                if (Schema::hasColumn('puppies', 'health_notes')) {
                    $row['health_notes'] = $healthNotesPool[array_rand($healthNotesPool)];
                }
                Puppy::create($row);
            }
        }
    }
}
