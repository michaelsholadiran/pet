<div class="product-review-form">
    @if (filled($reviewSuccessMessage))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
            {{ $reviewSuccessMessage }}
        </div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-600">Bought this product? Share your experience with other puppy parents.</p>
        <button
            type="button"
            wire:click="toggleReviewForm"
            class="shrink-0 inline-flex items-center justify-center gap-2 rounded-full border-2 border-primary bg-white px-5 py-2.5 text-sm font-semibold text-primary hover:bg-primary hover:text-white transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            {{ $showReviewForm ? 'Cancel' : 'Write a review' }}
        </button>
    </div>

    @if ($showReviewForm)
        <form wire:submit="submitReview" class="mt-6 space-y-5 rounded-xl border border-gray-200 bg-white p-6">
            <div>
                <span class="block text-sm font-medium text-gray-700 mb-2">Rating</span>
                <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Rating out of 5">
                    @foreach (range(1, 5) as $star)
                        <button
                            type="button"
                            wire:click="setReviewRating({{ $star }})"
                            class="min-w-9 h-9 rounded-full border text-sm font-semibold focus:outline-none focus-visible:ring-2 focus-visible:ring-primary {{ $reviewRating === $star ? 'border-primary bg-primary text-white' : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400' }}"
                            aria-label="Rate {{ $star }} out of 5"
                            aria-pressed="{{ $reviewRating === $star ? 'true' : 'false' }}"
                        >
                            {{ $star }}
                        </button>
                    @endforeach
                    <span class="ml-1 text-sm text-gray-500">{{ $reviewRating }} / 5</span>
                </div>
                @error('reviewRating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="review-title" class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-gray-400 font-normal">(optional)</span></label>
                <input
                    id="review-title"
                    type="text"
                    wire:model="reviewTitle"
                    class="w-full rounded-full border border-gray-300 px-3 py-2 text-gray-900 focus:border-primary focus:ring-1 focus:ring-primary"
                    maxlength="255"
                    placeholder="e.g. Perfect for our anxious pup"
                >
                @error('reviewTitle')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="review-comment" class="block text-sm font-medium text-gray-700 mb-1">Your review</label>
                <textarea
                    id="review-comment"
                    wire:model="reviewComment"
                    rows="5"
                    class="w-full rounded-full border border-gray-300 px-3 py-2 text-gray-900 focus:border-primary focus:ring-1 focus:ring-primary"
                    placeholder="What did you like? How did your puppy respond? (at least 10 characters)"
                    required
                ></textarea>
                @error('reviewComment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="review-name" class="block text-sm font-medium text-gray-700 mb-1">Your name</label>
                    <input
                        id="review-name"
                        type="text"
                        wire:model="reviewAuthorName"
                        class="w-full rounded-full border border-gray-300 px-3 py-2 text-gray-900 focus:border-primary focus:ring-1 focus:ring-primary"
                        autocomplete="name"
                        required
                    >
                    @error('reviewAuthorName')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="review-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        id="review-email"
                        type="email"
                        wire:model="reviewAuthorEmail"
                        class="w-full rounded-full border border-gray-300 px-3 py-2 text-gray-900 focus:border-primary focus:ring-1 focus:ring-primary"
                        autocomplete="email"
                        required
                    >
                    @error('reviewAuthorEmail')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="review-breed" class="block text-sm font-medium text-gray-700 mb-1">Breed <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input
                        id="review-breed"
                        type="text"
                        wire:model="reviewBreed"
                        class="w-full rounded-full border border-gray-300 px-3 py-2 text-gray-900 focus:border-primary focus:ring-1 focus:ring-primary"
                        placeholder="e.g. Golden Retriever"
                    >
                    @error('reviewBreed')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="review-puppy-age" class="block text-sm font-medium text-gray-700 mb-1">Puppy age (weeks) <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input
                        id="review-puppy-age"
                        type="number"
                        wire:model="reviewPuppyAgeWeeks"
                        min="1"
                        max="520"
                        class="w-full rounded-full border border-gray-300 px-3 py-2 text-gray-900 focus:border-primary focus:ring-1 focus:ring-primary"
                        placeholder="e.g. 12"
                    >
                    @error('reviewPuppyAgeWeeks')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <p class="text-xs text-gray-500">Reviews are moderated and may take a short time to appear.</p>

            <div class="flex flex-wrap gap-3">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center rounded-full bg-primary px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-dark transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="submitReview">Submit review</span>
                    <span wire:loading wire:target="submitReview">Submitting…</span>
                </button>
                <button
                    type="button"
                    wire:click="toggleReviewForm"
                    class="inline-flex items-center justify-center rounded-full border border-gray-300 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Cancel
                </button>
            </div>
        </form>
    @endif
</div>
