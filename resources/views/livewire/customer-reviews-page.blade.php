<div>
    <h1 class="font-display text-2xl font-bold text-gray-900 mb-2">Reviews</h1>
    <p class="text-gray-600 mb-8">Share feedback on products you’ve purchased. Edits are re-checked before they go live.</p>

    @if ($message)
        <div class="mb-6 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg px-4 py-3">{{ $message }}</div>
    @endif

    @if ($eligibleProducts->isNotEmpty())
        <section class="rounded-xl border border-gray-200 bg-white p-6 mb-10">
            <h2 class="font-display text-lg font-bold text-gray-900 mb-2">Leave a review</h2>
            <p class="text-sm text-gray-600 mb-6">You can review each purchased product once.</p>
            <form wire:submit="saveNewReview" class="space-y-4 max-w-xl">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                    <select wire:model="new_product_id" class="w-full rounded-full border border-gray-300 px-3 py-2">
                        <option value="">Choose a product</option>
                        @foreach ($eligibleProducts as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    @error('new_product_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <select wire:model="new_rating" class="w-full rounded-full border border-gray-300 px-3 py-2">
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} — {{ ['','Poor','Fair','Good','Very good','Excellent'][$i] }}</option>
                        @endfor
                    </select>
                    @error('new_rating') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="text" wire:model="new_title" class="w-full rounded-full border border-gray-300 px-3 py-2">
                    @error('new_title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Comment <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea wire:model="new_comment" rows="4" class="w-full rounded-full border border-gray-300 px-3 py-2"></textarea>
                    @error('new_comment') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Breed <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="text" wire:model="new_breed" class="w-full rounded-full border border-gray-300 px-3 py-2">
                        @error('new_breed') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Puppy age (weeks) <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="number" wire:model="new_puppy_age_weeks" min="0" max="520" class="w-full rounded-full border border-gray-300 px-3 py-2">
                        @error('new_puppy_age_weeks') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <button type="submit" wire:loading.attr="disabled" class="rounded-full bg-primary text-white font-semibold px-6 py-2.5 hover:bg-primary-dark disabled:opacity-60">Submit review</button>
            </form>
        </section>
    @elseif ($reviews->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 bg-white p-8 text-center text-gray-600 mb-10">
            <p>Purchase and receive a product to leave your first review.</p>
            <a href="{{ route('products.index') }}" class="inline-block mt-4 text-primary font-semibold hover:underline">Browse shop</a>
        </div>
    @endif

    <section class="rounded-xl border border-gray-200 bg-white p-6">
        <h2 class="font-display text-lg font-bold text-gray-900 mb-6">Your reviews</h2>
        @if ($reviews->isEmpty())
            <p class="text-gray-600 text-sm">No reviews yet.</p>
        @else
            <ul class="space-y-6">
                @foreach ($reviews as $review)
                    <li class="border border-gray-100 rounded-lg p-4">
                        @if ($editingReviewId === $review->id)
                            <form wire:submit="saveEditedReview" class="space-y-4 max-w-xl">
                                <p class="text-sm font-medium text-gray-900">{{ $review->product?->name }}</p>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                    <select wire:model="edit_rating" class="w-full rounded-full border border-gray-300 px-3 py-2">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                    <input type="text" wire:model="edit_title" class="w-full rounded-full border border-gray-300 px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                                    <textarea wire:model="edit_comment" rows="3" class="w-full rounded-full border border-gray-300 px-3 py-2"></textarea>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Breed</label>
                                        <input type="text" wire:model="edit_breed" class="w-full rounded-full border border-gray-300 px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Puppy age (weeks)</label>
                                        <input type="number" wire:model="edit_puppy_age_weeks" min="0" max="520" class="w-full rounded-full border border-gray-300 px-3 py-2">
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="rounded-full bg-primary text-white font-semibold px-5 py-2 hover:bg-primary-dark">Save</button>
                                    <button type="button" wire:click="cancelEditReview" class="rounded-full border border-gray-300 font-semibold px-5 py-2 hover:bg-gray-50">Cancel</button>
                                </div>
                            </form>
                        @else
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                <div>
                                    @if ($review->product)
                                        <a href="{{ route('products.show', $review->product->slug) }}" class="font-semibold text-gray-900 hover:text-primary">{{ $review->product->name }}</a>
                                    @else
                                        <span class="font-semibold text-gray-900">Product removed</span>
                                    @endif
                                    <p class="text-sm text-gray-600 mt-1">{{ $review->rating }}/5 · {{ $review->created_at->format('M j, Y') }}</p>
                                    @if ($review->title)
                                        <p class="font-medium text-gray-900 mt-2">{{ $review->title }}</p>
                                    @endif
                                    @if ($review->comment)
                                        <p class="text-sm text-gray-700 mt-1">{{ $review->comment }}</p>
                                    @endif
                                    <p class="text-xs mt-2 {{ $review->is_approved ? 'text-green-700' : 'text-amber-700' }}">
                                        {{ $review->is_approved ? 'Published' : 'Pending moderation' }}
                                    </p>
                                </div>
                                <button type="button" wire:click="startEditReview({{ $review->id }})" class="shrink-0 text-sm font-semibold text-primary hover:underline">Edit</button>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
            <div class="mt-6">{{ $reviews->links() }}</div>
        @endif
    </section>
</div>
