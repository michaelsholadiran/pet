<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::where('is_active', true)->get();

        if ($products->isEmpty()) {
            $this->command->warn('Run ProductSeeder first.');

            return;
        }

        $titles = [
            'Absolutely love it!',
            'Great quality, fast shipping',
            'My puppy adores this',
            'Exactly what we needed',
            'Highly recommend',
            'Best purchase for our new pup',
            'Durable and well made',
            'Perfect for training',
        ];

        $comments = [
            'My puppy took to it immediately. Great product.',
            'Arrived quickly and exactly as described. Very happy with this purchase.',
            'We\'ve tried many similar products and this is by far the best.',
            'Quality is outstanding. Will definitely buy again.',
        ];

        $breeds = ['Golden Retriever', 'Labrador', 'Mixed', 'Poodle', null];

        foreach ($products as $product) {
            $reviewCount = rand(2, 6);
            for ($i = 0; $i < $reviewCount; $i++) {
                $user = $users->isNotEmpty() ? $users->random() : null;

                Review::create([
                    'user_id' => $user?->id,
                    'product_id' => $product->id,
                    'rating' => rand(4, 5),
                    'title' => $titles[array_rand($titles)],
                    'comment' => $comments[array_rand($comments)],
                    'is_approved' => (bool) rand(0, 1),
                    'is_featured' => rand(1, 10) === 1,
                    'puppy_age_at_review' => rand(8, 24),
                    'breed' => $breeds[array_rand($breeds)],
                    'author_name' => $user?->name ?? fake()->name(),
                    'author_email' => $user?->email ?? fake()->safeEmail(),
                ]);
            }
        }
    }
}
