<?php

namespace App\Livewire;

use App\Data\ProductsData;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

class StarterKitPage extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $bundles = Product::query()
            ->where('is_active', true)
            ->where('catalog_type', Product::CATALOG_BUNDLE)
            ->with(['bundleItems.componentProduct.images', 'images'])
            ->orderBy('name')
            ->get();
        $productsData = ProductsData::all();
        $featuredSlugs = ['calming-dog-bed', 'no-pull-harness', 'grooming-glove', 'indestructible-chew-toy', 'feeding-bowl'];
        $featured = collect($productsData)->filter(fn ($p) => in_array($p['slug'] ?? '', $featuredSlugs) && ! empty($p['published'] ?? true))->values()->all();

        view()->share([
            'page_title' => 'Starter Kit - '.config('puppiary.name'),
            'page_description' => 'Curated starter kit for new puppy parents. Everything your pup needs in one bundle.',
            'page_canonical' => '/starter-kit',
        ]);

        return view('livewire.starter-kit-page', [
            'bundles' => $bundles,
            'featured' => $featured,
        ]);
    }
}
