<?php

namespace App\Livewire;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerReviewsPage extends Component
{
    use WithPagination;

    public ?int $editingReviewId = null;

    public ?int $new_product_id = null;

    public int $new_rating = 5;

    public string $new_title = '';

    public string $new_comment = '';

    public string $new_breed = '';

    public ?int $new_puppy_age_weeks = null;

    public int $edit_rating = 5;

    public string $edit_title = '';

    public string $edit_comment = '';

    public string $edit_breed = '';

    public ?int $edit_puppy_age_weeks = null;

    public ?string $message = null;

    #[Layout('layouts.dashboard')]
    public function mount(): void
    {
        view()->share('dashboardPageTitle', 'My reviews');
    }

    public function startEditReview(int $reviewId): void
    {
        $review = Review::query()
            ->where('user_id', auth()->id())
            ->with('product')
            ->findOrFail($reviewId);

        $this->editingReviewId = $review->id;
        $this->edit_rating = (int) $review->rating;
        $this->edit_title = (string) ($review->title ?? '');
        $this->edit_comment = (string) ($review->comment ?? '');
        $this->edit_breed = (string) ($review->breed ?? '');
        $this->edit_puppy_age_weeks = $review->puppy_age_at_review;
        $this->message = null;
    }

    public function cancelEditReview(): void
    {
        $this->editingReviewId = null;
        $this->resetValidation();
    }

    public function saveNewReview(): void
    {
        $eligibleIds = $this->eligibleProductIds();

        $this->validate([
            'new_product_id' => ['required', 'integer', Rule::in($eligibleIds->all())],
            'new_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'new_title' => ['nullable', 'string', 'max:255'],
            'new_comment' => ['nullable', 'string', 'max:5000'],
            'new_breed' => ['nullable', 'string', 'max:255'],
            'new_puppy_age_weeks' => ['nullable', 'integer', 'min:0', 'max:520'],
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $this->new_product_id,
            'rating' => $this->new_rating,
            'title' => $this->new_title !== '' ? $this->new_title : null,
            'comment' => $this->new_comment !== '' ? $this->new_comment : null,
            'breed' => $this->new_breed !== '' ? $this->new_breed : null,
            'puppy_age_at_review' => $this->new_puppy_age_weeks,
            'is_approved' => false,
            'author_name' => auth()->user()->name,
            'author_email' => auth()->user()->email,
        ]);

        $this->reset(['new_product_id', 'new_title', 'new_comment', 'new_breed', 'new_puppy_age_weeks']);
        $this->new_rating = 5;
        $this->message = 'Thanks! Your review was submitted and will appear after a quick check.';
        $this->resetPage();
    }

    public function saveEditedReview(): void
    {
        if (! $this->editingReviewId) {
            return;
        }

        $this->validate([
            'edit_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'edit_title' => ['nullable', 'string', 'max:255'],
            'edit_comment' => ['nullable', 'string', 'max:5000'],
            'edit_breed' => ['nullable', 'string', 'max:255'],
            'edit_puppy_age_weeks' => ['nullable', 'integer', 'min:0', 'max:520'],
        ]);

        $review = Review::query()
            ->where('user_id', auth()->id())
            ->findOrFail($this->editingReviewId);

        $review->update([
            'rating' => $this->edit_rating,
            'title' => $this->edit_title !== '' ? $this->edit_title : null,
            'comment' => $this->edit_comment !== '' ? $this->edit_comment : null,
            'breed' => $this->edit_breed !== '' ? $this->edit_breed : null,
            'puppy_age_at_review' => $this->edit_puppy_age_weeks,
            'is_approved' => false,
        ]);

        $this->editingReviewId = null;
        $this->message = 'Review updated. It may be hidden briefly while we re-check it.';
    }

    /**
     * @return \Illuminate\Support\Collection<int, int>
     */
    private function eligibleProductIds(): \Illuminate\Support\Collection
    {
        $user = auth()->user();
        $reviewed = $user->reviews()->pluck('product_id');

        return OrderItem::query()
            ->whereHas('order', function ($q) use ($user): void {
                $q->where(function ($q2) use ($user): void {
                    $q2->where('user_id', $user->id);
                    if (filled($user->email)) {
                        $q2->orWhere('email', $user->email);
                    }
                })->whereIn('status', ['paid', 'shipped', 'delivered']);
            })
            ->whereNotNull('product_id')
            ->distinct()
            ->pluck('product_id')
            ->diff($reviewed)
            ->values();
    }

    public function render()
    {
        $eligibleIds = $this->eligibleProductIds();
        $eligibleProducts = Product::query()
            ->whereIn('id', $eligibleIds)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $reviews = auth()->user()
            ->reviews()
            ->with('product')
            ->latest()
            ->paginate(8);

        return view('livewire.customer-reviews-page', [
            'eligibleProducts' => $eligibleProducts,
            'reviews' => $reviews,
        ]);
    }
}
