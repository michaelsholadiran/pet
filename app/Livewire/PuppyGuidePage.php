<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PuppyGuidePage extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $categories = Category::orderBy('name')->get();
        $articles = Article::where('is_published', true)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        view()->share([
            'page_title' => 'Puppy Guide - '.config('puppiary.name'),
            'page_description' => 'Learn everything about raising a happy, healthy puppy. Potty training, socialization, feeding, and more.',
            'page_canonical' => '/guide/puppy',
        ]);

        return view('livewire.puppy-guide-page', [
            'categories' => $categories,
            'articles' => $articles,
        ]);
    }
}
