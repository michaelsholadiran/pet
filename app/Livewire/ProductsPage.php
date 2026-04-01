<?php

namespace App\Livewire;

use App\Data\ProductsData;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class ProductsPage extends Component
{
    #[Url]
    public string $search = '';

    public string $category = '';

    public function mount(?string $category = null): void
    {
        $legacy = request()->query('category');
        if (is_string($legacy) && $legacy !== '' && isset(self::categoryMapping()[$legacy])) {
            $this->redirect(route('products.category', ['category' => $legacy]), navigate: false);

            return;
        }

        if ($category !== null && $category !== '' && ! isset(self::categoryMapping()[$category])) {
            abort(404);
        }

        $this->category = $category ?? '';
    }

    public static function shopCategories(): array
    {
        return [
            'food' => 'Food',
            'treats' => 'Treats',
            'essentials' => 'Essentials',
        ];
    }

    public static function categoryMapping(): array
    {
        return [
            'food' => ['Feeding'],
            'treats' => ['Play & Teething'],
            'essentials' => ['Grooming & Comfort', 'Training & Safety'],
        ];
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $products = ProductsData::all();
        $filtered = collect($products)->filter(function ($p) {
            if (empty($p['published'])) {
                return false;
            }
            if (isset($p['list_in_catalog']) && $p['list_in_catalog'] === false) {
                return false;
            }
            if (($p['isBundle'] ?? false) === true) {
                return false;
            }

            return true;
        });

        if ($this->category !== '' && isset(self::categoryMapping()[$this->category])) {
            $cats = self::categoryMapping()[$this->category];
            $filtered = $filtered->filter(fn ($p) => in_array($p['category'] ?? '', $cats));
        }

        if ($this->search !== '') {
            $searchLower = mb_strtolower($this->search);
            $filtered = $filtered->filter(function ($p) use ($searchLower) {
                $name = mb_strtolower($p['name'] ?? '');
                $short = mb_strtolower($p['shortDescription'] ?? '');

                return str_contains($name, $searchLower) || str_contains($short, $searchLower);
            });
        }

        $filtered = $filtered->values()->all();
        $inStock = array_filter($filtered, fn ($p) => ($p['stock'] ?? 0) > 0);
        $outOfStock = array_filter($filtered, fn ($p) => ($p['stock'] ?? 0) <= 0);
        $showRestocking = count($outOfStock) > count($inStock) && count($outOfStock) >= 3;

        if ($this->category !== '') {
            view()->share([
                'page_canonical' => '/products/'.$this->category,
                'page_title' => (self::shopCategories()[$this->category] ?? 'Shop').' - '.config('puppiary.name'),
            ]);
        }

        return view('livewire.products-page', [
            'products' => $products,
            'filtered' => $filtered,
            'inStock' => array_values($inStock),
            'showRestocking' => $showRestocking,
            'currentCategory' => $this->category,
        ]);
    }
}
