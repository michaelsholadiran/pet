<div class="max-w-[1200px] mx-auto px-6 sm:px-8 py-8">
    <section class="mb-6">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 text-center">Shop</h1>
        <div class="mt-4 flex justify-center">
        <form
            method="get"
            action="{{ $currentCategory ? route('products.category', ['category' => $currentCategory]) : route('products.index') }}"
            class="mx-auto flex w-full max-w-xl flex-col items-stretch gap-3 sm:max-w-3xl sm:flex-row sm:items-center sm:justify-center"
            x-data="{
                query: @js($search),
                clearSearch() {
                    this.query = '';
                    this.$nextTick(() => this.$el.requestSubmit());
                }
            }"
        >
            <label for="shop-search" class="sr-only">Search products</label>
            <div class="relative min-w-0 flex-1 sm:max-w-md">
                <span class="pointer-events-none absolute left-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center text-gray-500" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input
                    id="shop-search"
                    name="search"
                    type="text"
                    x-model="query"
                    placeholder="Search products..."
                    class="w-full rounded-full border-0 bg-gray-100 py-3 pl-12 pr-12 text-base text-gray-900 placeholder:text-gray-500 focus:bg-white focus:ring-2 focus:ring-primary/25 focus:outline-none"
                >
                <button
                    type="button"
                    x-show="query && query.length > 0"
                    x-cloak
                    @click="clearSearch()"
                    class="absolute right-2 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full text-gray-500 transition hover:bg-gray-200 hover:text-gray-800"
                    aria-label="Clear search"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <button
                type="submit"
                class="inline-flex w-full shrink-0 items-center justify-center rounded-full bg-primary px-8 py-3 text-base font-semibold text-white transition hover:bg-primary-dark sm:w-auto"
            >
                Search
            </button>
        </form>
        </div>
    </section>

    <section class="mb-8">
        <div class="flex flex-wrap gap-2">
            <a
                href="{{ route('products.index') }}"
                class="px-4 py-2 rounded-full text-sm font-medium border transition no-underline {{ $currentCategory === '' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-700 border-gray-300 hover:border-primary/40' }}"
            >
                All
            </a>
            @foreach (\App\Livewire\ProductsPage::shopCategories() as $slug => $label)
                <a
                    href="{{ route('products.category', ['category' => $slug]) }}"
                    class="px-4 py-2 rounded-full text-sm font-medium border transition no-underline {{ $currentCategory === $slug ? 'bg-primary text-white border-primary' : 'bg-white text-gray-700 border-gray-300 hover:border-primary/40' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </section>

    @if (count($filtered) === 0)
        <section class="bg-white border border-gray-200 rounded-2xl p-8 text-center text-gray-600">
            No products found.
        </section>
    @else
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($filtered as $p)
                @php
                    $dp = \App\Helpers\CurrencyHelper::formatProductPrice($p);
                @endphp
                <article class="rounded-xl overflow-hidden border border-gray-200 bg-white hover:border-primary/30 transition">
                    <a href="{{ route('products.show', $p['slug']) }}" class="block no-underline">
                        <img src="{{ $p['images'][0] }}" alt="{{ $p['name'] }}" class="w-full aspect-4/3 object-cover" loading="lazy">
                    </a>
                    <div class="p-4">
                        <a href="{{ route('products.show', $p['slug']) }}" class="block no-underline">
                            <h2 class="font-semibold text-lg text-gray-900">{{ $p['name'] }}</h2>
                        </a>
                        <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ $p['shortDescription'] }}</p>
                        <div class="mt-4 flex items-center justify-between gap-3">
                            <span class="font-bold text-primary">{{ $dp['symbol'] }}{{ $dp['formatted'] }}</span>
                            <button type="button" class="add-to-cart-btn px-4 py-2 rounded-full bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition" data-product-id="{{ $p['id'] }}">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
    @endif
</div>

@push('scripts')
<script>
    window.products = @json($products);
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.add-to-cart-btn');
        if (!btn) return;
        e.preventDefault(); e.stopPropagation();
        const id = btn.getAttribute('data-product-id');
        const product = (window.products||[]).find(p => p.id == id);
        if (product && typeof addToCart === 'function') {
            addToCart(product, 1);
            btn.textContent = 'Added!';
            setTimeout(() => { btn.textContent = 'Add to Cart'; }, 1500);
        }
    });
</script>
@endpush
