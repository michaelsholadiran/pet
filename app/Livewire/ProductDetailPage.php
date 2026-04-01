<?php

namespace App\Livewire;

use App\Helpers\CurrencyHelper;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ProductDetailPage extends Component
{
    public string $slug;

    public int $productId;

    public bool $showReviewForm = false;

    public int $reviewRating = 5;

    public string $reviewTitle = '';

    public string $reviewComment = '';

    public string $reviewAuthorName = '';

    public string $reviewAuthorEmail = '';

    public $reviewPuppyAgeWeeks = null;

    public string $reviewBreed = '';

    public ?string $reviewSuccessMessage = null;

    #[Layout('layouts.app')]
    public function mount(string $slug): void
    {
        $this->slug = $slug;

        $id = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->value('id');

        if (! $id) {
            abort(404);
        }

        $this->productId = (int) $id;

        if (Auth::check()) {
            $user = Auth::user();
            $this->reviewAuthorName = (string) ($user->name ?? '');
            $this->reviewAuthorEmail = (string) ($user->email ?? '');
        }
    }

    public function toggleReviewForm(): void
    {
        $this->showReviewForm = ! $this->showReviewForm;
        $this->resetValidation();
        if ($this->showReviewForm) {
            $this->reviewSuccessMessage = null;
        }
    }

    public function setReviewRating(int $value): void
    {
        $this->reviewRating = max(1, min(5, $value));
    }

    public function submitReview(): void
    {
        $this->reviewPuppyAgeWeeks = $this->reviewPuppyAgeWeeks === '' || $this->reviewPuppyAgeWeeks === null
            ? null
            : (int) $this->reviewPuppyAgeWeeks;

        $this->validate([
            'reviewRating' => ['required', 'integer', 'min:1', 'max:5'],
            'reviewTitle' => ['nullable', 'string', 'max:255'],
            'reviewComment' => ['required', 'string', 'min:10', 'max:5000'],
            'reviewAuthorName' => ['required', 'string', 'max:255'],
            'reviewAuthorEmail' => ['required', 'email', 'max:255'],
            'reviewPuppyAgeWeeks' => ['nullable', 'integer', 'min:1', 'max:520'],
            'reviewBreed' => ['nullable', 'string', 'max:255'],
        ]);

        Product::query()
            ->whereKey($this->productId)
            ->where('is_active', true)
            ->firstOrFail();

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $this->productId,
            'rating' => $this->reviewRating,
            'title' => filled($this->reviewTitle) ? $this->reviewTitle : null,
            'comment' => $this->reviewComment,
            'is_approved' => false,
            'is_featured' => false,
            'puppy_age_at_review' => $this->reviewPuppyAgeWeeks,
            'breed' => filled($this->reviewBreed) ? $this->reviewBreed : null,
            'author_name' => $this->reviewAuthorName,
            'author_email' => $this->reviewAuthorEmail,
        ]);

        $this->reset('reviewComment', 'reviewTitle');
        $this->reviewRating = 5;
        $this->reviewPuppyAgeWeeks = null;
        $this->reviewBreed = '';

        if (Auth::check()) {
            $user = Auth::user();
            $this->reviewAuthorName = (string) ($user->name ?? '');
            $this->reviewAuthorEmail = (string) ($user->email ?? '');
        } else {
            $this->reset('reviewAuthorName', 'reviewAuthorEmail');
        }

        $this->showReviewForm = false;
        $this->reviewSuccessMessage = 'Thanks! Your review was submitted and will appear on this page after we approve it.';
    }

    public function render()
    {
        $model = Product::query()
            ->where('slug', $this->slug)
            ->where('is_active', true)
            ->with([
                'images',
                'reviews' => fn ($q) => $q
                    ->where('is_approved', true)
                    ->orderByDesc('created_at'),
            ])
            ->first();

        if (! $model) {
            abort(404);
        }

        $product = $model->toCatalogArray();
        $dp = CurrencyHelper::formatProductPrice($product);
        $isBundle = $model->isBundle();
        if ($isBundle && $model->original_price) {
            $compareAtPrice = (float) $model->original_price;
            $listPrice = (float) ($model->sale_price ?? $model->price);
        } else {
            $listPrice = (float) ($model->sale_price ?? $model->price);
            $compareAtPrice = $model->sale_price ? (float) $model->price : null;
        }
        $categorySlug = null;
        foreach (ProductsPage::categoryMapping() as $slug => $labels) {
            if (in_array($product['category'] ?? '', $labels, true)) {
                $categorySlug = $slug;
                break;
            }
        }

        $related = collect(\App\Data\ProductsData::all())
            ->filter(function (array $p) use ($product): bool {
                return ! empty($p['published'])
                    && ($p['slug'] ?? '') !== ($product['slug'] ?? '')
                    && ($p['category'] ?? '') === ($product['category'] ?? '')
                    && empty($p['isBundle']);
            })
            ->take(3)
            ->values()
            ->all();

        $og = $product['images'][0] ?? null;
        if (is_string($og) && str_starts_with($og, '/')) {
            $og = url($og);
        }

        view()->share([
            'page_title' => $product['name'].' - Puppiary',
            'page_description' => $product['shortDescription'] ?? 'Shop quality puppy and dog products at Puppiary.',
            'page_canonical' => '/product/'.$this->slug,
            'page_og_image' => $og,
        ]);

        return view('livewire.product-detail-page', [
            'product' => $product,
            'displayPrice' => $dp,
            'isBundle' => $isBundle,
            'listPrice' => $listPrice,
            'compareAtPrice' => $compareAtPrice,
            'categorySlug' => $categorySlug,
            'related' => $related,
            'reviews' => $model->reviews,
        ]);
    }
}
