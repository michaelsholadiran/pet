<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Services\RandomUserService;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->command->warn('Run CategorySeeder first.');

            return;
        }

        $titles = [
            'Getting Started with Your New Puppy',
            'The First 24 Hours: What to Expect',
            'Puppy Potty Training 101',
            'Essential Supplies for Your Puppy\'s First Month',
            'Understanding Puppy Nutrition: A Complete Guide',
            'How to Socialize Your Puppy Safely',
            'Bite Inhibition: Teaching Gentle Play',
            'Crate Training Made Easy',
            'The Best Toys for Teething Puppies',
            'Puppy-Proofing Your Home',
        ];

        foreach ($titles as $i => $title) {
            $slug = \Illuminate\Support\Str::slug($title);
            if (Article::where('slug', $slug)->exists()) {
                continue;
            }

            $imgUrl = RandomUserService::imageUrl();
            $path = RandomUserService::downloadAndStore($imgUrl, 'articles', 'article-'.($i + 1));

            Article::create([
                'title' => $title,
                'slug' => $slug,
                'content' => '<p>'.implode('</p><p>', fake()->paragraphs(5)).'</p>',
                'category_id' => $categories->random()->id,
                'featured_image' => $path,
                'is_published' => $i < 6,
            ]);
        }
    }
}
