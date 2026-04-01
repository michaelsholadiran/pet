@php
    $sym = \App\Helpers\CurrencyHelper::symbol();
@endphp

<div class="space-y-16 sm:space-y-20 pb-16">
    <section class="px-6 pt-10">
        <div class="max-w-[1200px] mx-auto rounded-3xl overflow-hidden border border-gray-200 bg-white">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="p-8 sm:p-10 lg:p-12 flex flex-col justify-center">
                    <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">The Complete Starter Kit</h1>
                    <p class="text-lg text-gray-700 mb-2">Everything you need for your puppy's first weeks — in one simple box.</p>
                    <p class="text-lg text-gray-600 mb-1">No running to the store. No guessing what to buy.</p>
                    <p class="text-lg text-gray-600 mb-8">Just open the box and feel confident from day one.</p>
                    <div>
                        <button type="button" data-original-label="Add to Cart – {{ $sym }}127.49" class="starter-kit-add-btn inline-flex items-center justify-center px-8 py-4 rounded-full bg-primary text-white font-semibold text-lg no-underline hover:bg-primary-dark transition">
                            Add to Cart – {{ $sym }}127.49
                        </button>
                        <p class="text-sm text-gray-500 mt-3">Free shipping • 30-day happiness guarantee</p>
                    </div>
                </div>
                <div class="min-h-[360px]">
                    <img src="{{ asset('images/puppiary-homepage-promotional-banner.webp') }}" alt="Peaceful puppy resting in cozy bed" class="w-full h-full object-cover" loading="eager" />
                </div>
            </div>
        </div>
    </section>

    <section class="px-6">
        <div class="max-w-[960px] mx-auto">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-8 text-center">What's Inside the Box</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">1</span><p class="text-gray-800">Cozy washable bed for better sleep</p></div>
                <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">2</span><p class="text-gray-800">Two stainless steel bowls (safe, no plastic)</p></div>
                <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">3</span><p class="text-gray-800">Puppy food for the first weeks</p></div>
                <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">4</span><p class="text-gray-800">Gentle grooming tools (brush, wipes, nail clippers)</p></div>
                <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">5</span><p class="text-gray-800">Training treats and a teething toy</p></div>
                <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-start gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">6</span><p class="text-gray-800">Simple step-by-step guide for the first 30 days</p></div>
            </div>
        </div>
    </section>

    <section class="px-6">
        <div class="max-w-[960px] mx-auto">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">Why Parents Love This Kit</h2>
            <div class="rounded-2xl border border-gray-200 bg-white p-7 mb-6">
                <p class="text-lg text-gray-800 mb-3">"It made the first days so much easier. Our puppy settled in quickly."</p>
                <p class="text-sm font-semibold text-gray-600">— Sarah & Max</p>
            </div>
            <p class="text-lg text-gray-700">Over 2,300 happy new puppy families have started with this kit.</p>
        </div>
    </section>

    <section class="px-6">
        <div class="max-w-[960px] mx-auto bg-white rounded-2xl border border-gray-200 p-8">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">Perfect If...</h2>
            <ul class="space-y-3 mb-7 text-gray-800">
                <li>• You just brought your puppy home</li>
                <li>• You want everything ready without stress</li>
                <li>• You like clear, simple instructions</li>
            </ul>
            <button type="button" data-original-label="Add to Cart" class="starter-kit-add-btn inline-flex items-center justify-center px-8 py-4 rounded-full bg-primary text-white font-semibold text-lg no-underline hover:bg-primary-dark transition">
                Add to Cart
            </button>
        </div>
    </section>

    <section class="px-6">
        <div class="max-w-[960px] mx-auto bg-primary/10 rounded-2xl border border-primary/20 p-8">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Still Have Questions?</h2>
            <p class="text-gray-700 mb-6">We're real people who love puppies. Call us or send a message — we reply quickly and happily help you.</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-7 py-3 rounded-full bg-primary text-white font-semibold no-underline hover:bg-primary-dark transition">
                Contact Us
            </a>
        </div>
    </section>
</div>

@push('scripts')
<script>
    window.products = @json(\App\Data\ProductsData::all());

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.starter-kit-add-btn');
        if (!btn) return;

        const products = window.products || [];
        const product =
            products.find((p) => p.slug === 'complete-starter-bundle') ||
            products.find((p) => (p.slug || '').includes('starter') && (p.name || '').toLowerCase().includes('complete')) ||
            products.find((p) => (p.slug || '').includes('starter'));

        if (product && typeof addToCart === 'function') {
            const originalLabel = btn.getAttribute('data-original-label') || 'Add to Cart';
            addToCart(product, 1);
            btn.textContent = 'Added!';
            setTimeout(() => {
                btn.textContent = originalLabel;
            }, 1200);
        }
    });
</script>
@endpush
