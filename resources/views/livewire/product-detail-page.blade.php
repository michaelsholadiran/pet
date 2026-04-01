<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <div class="py-4">
        <nav class="flex text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
            <span class="mx-2">/</span>
            @if ($categorySlug)
                <a href="{{ route('products.category', ['category' => $categorySlug]) }}" class="hover:text-primary">{{ $product['category'] }}</a>
            @else
                <a href="{{ route('products.index') }}" class="hover:text-primary">Shop</a>
            @endif
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium">{{ $product['name'] }}</span>
        </nav>
    </div>

    <main class="py-4">
        <div class="grid md:grid-cols-2 gap-12">
            <x-product-gallery
                :images="$product['images']"
                :slug="$slug"
                :product-name="$product['name']"
            />

            <div x-data="{
                quantity: 1,
                activeSize: '5lb',
                sizes: ['5lb', '12lb', '25lb'],
                subscription: false,
                basePrice: {{ (float) $listPrice }},
                get sizeMultiplier() {
                    return this.activeSize === '5lb' ? 1 : (this.activeSize === '12lb' ? 1.9 : 3.4);
                },
                get unitPrice() {
                    return Number((this.basePrice * this.sizeMultiplier).toFixed(2));
                },
                get finalPrice() {
                    return Number((this.subscription ? this.unitPrice * 0.9 : this.unitPrice).toFixed(2));
                }
            }">
                <div class="flex items-center gap-2 mb-3">
                    @if ($isBundle)
                        @if (($product['stock'] ?? 0) > 0)
                            <span class="bg-primary/10 text-primary text-sm font-semibold px-3 py-1 rounded-full">In Stock</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-sm font-semibold px-3 py-1 rounded-full">Out of Stock</span>
                        @endif
                        @if (($product['stock'] ?? 0) > 10)
                            <span class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">Best Value</span>
                        @endif
                    @else
                        <span class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">Vet Recommended</span>
                        <span class="bg-amber-100 text-amber-700 text-sm font-semibold px-3 py-1 rounded-full">Grain-Free Option</span>
                    @endif
                </div>

                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">{{ $product['name'] }}</h1>
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-sm font-medium text-gray-700">5.0</span>
                    <span class="text-sm text-gray-500">({{ $reviews->count() }} reviews)</span>
                </div>

                @if ($isBundle)
                    <div class="mb-6">
                        @if ($compareAtPrice)
                            <span class="text-2xl text-gray-400 line-through mr-3">{{ $displayPrice['symbol'] }}{{ number_format($compareAtPrice, 2) }}</span>
                        @endif
                        <span class="text-4xl font-bold text-primary">{{ $displayPrice['symbol'] }}{{ number_format($listPrice, 2) }}</span>
                        @if ($compareAtPrice && $compareAtPrice > $listPrice)
                            <span class="text-green-600 font-medium ml-3">Save {{ $displayPrice['symbol'] }}{{ number_format($compareAtPrice - $listPrice, 2) }}</span>
                        @endif
                    </div>
                @else
                    <div class="mb-6">
                        <template x-if="!subscription">
                            <div>
                                <span class="text-4xl font-bold text-primary" x-text="'{{ $displayPrice['symbol'] }}' + finalPrice.toFixed(2)"></span>
                                <span class="text-gray-500 ml-2" x-text="'/ ' + activeSize + ' bag'"></span>
                            </div>
                        </template>
                        <template x-if="subscription">
                            <div>
                                <span class="text-2xl text-gray-400 line-through mr-2" x-text="'{{ $displayPrice['symbol'] }}' + unitPrice.toFixed(2)"></span>
                                <span class="text-4xl font-bold text-primary" x-text="'{{ $displayPrice['symbol'] }}' + finalPrice.toFixed(2)"></span>
                                <span class="text-green-600 font-medium ml-2">Save 10%</span>
                                <p class="text-sm text-gray-500 mt-1">Free delivery every 4 weeks · Cancel anytime</p>
                            </div>
                        </template>
                    </div>
                @endif

                <div class="text-gray-600 mb-6 leading-relaxed space-y-4 [&_p]:mb-4 [&_p:last-child]:mb-0">
                    {!! $product['description'] !!}
                </div>

                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">{{ $isBundle ? "What's Included" : 'Key Benefits' }}</h3>
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-700">
                        @if ($isBundle && ! empty($product['bundleLines']))
                            @foreach ($product['bundleLines'] as $line)
                                <div class="flex items-center gap-2 col-span-2 sm:col-span-1">
                                    <span class="text-primary font-bold" aria-hidden="true">·</span>
                                    <span>{{ $line['name'] ?? 'Item' }} × {{ (int) ($line['quantity'] ?? 1) }}</span>
                                </div>
                            @endforeach
                        @elseif ($isBundle)
                            <div class="flex items-center gap-2"><span class="text-primary font-bold" aria-hidden="true">·</span> Curated starter essentials</div>
                            <div class="flex items-center gap-2"><span class="text-primary font-bold" aria-hidden="true">·</span> Practical day-one tools</div>
                            <div class="flex items-center gap-2"><span class="text-primary font-bold" aria-hidden="true">·</span> Better value than separate buys</div>
                            <div class="flex items-center gap-2"><span class="text-primary font-bold" aria-hidden="true">·</span> Easy first-week routine</div>
                        @else
                            <div class="flex items-center gap-2"><span class="text-primary font-bold" aria-hidden="true">·</span> Real protein first ingredient</div>
                            <div class="flex items-center gap-2"><span class="text-primary font-bold" aria-hidden="true">·</span> DHA for brain development</div>
                            <div class="flex items-center gap-2"><span class="text-primary font-bold" aria-hidden="true">·</span> No corn, wheat, or soy</div>
                            <div class="flex items-center gap-2"><span class="text-primary font-bold" aria-hidden="true">·</span> Omega support for coat health</div>
                            <div class="flex items-center gap-2"><span class="text-primary font-bold" aria-hidden="true">·</span> Immune-support antioxidants</div>
                            <div class="flex items-center gap-2"><span class="text-primary font-bold" aria-hidden="true">·</span> Vet-informed formulation</div>
                        @endif
                    </div>
                </div>

                @unless ($isBundle)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Size:</label>
                        <div class="flex gap-3">
                            <template x-for="size in sizes" :key="size">
                                <button @click="activeSize = size" type="button" class="px-5 py-2 rounded-full border-2 font-medium transition"
                                    :class="activeSize === size ? 'border-primary bg-primary/10 text-primary' : 'border-gray-300 text-gray-700 hover:border-gray-400'">
                                    <span x-text="size"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="mb-6 p-4 border border-primary/20 bg-primary/5 rounded-xl">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" x-model="subscription" class="w-5 h-5 text-primary rounded">
                            <div>
                                <span class="font-semibold text-gray-900">Subscribe & Save 10%</span>
                                <p class="text-sm text-gray-600">Auto-delivery every 4 weeks. Free shipping. Cancel anytime.</p>
                            </div>
                        </label>
                    </div>
                @endunless

                <div class="flex flex-wrap gap-4 mb-6">
                    <div class="flex items-center border border-gray-300 rounded-full overflow-hidden">
                        <button @click="if (quantity > 1) quantity--" type="button" class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition rounded-none">−</button>
                        <input type="number" x-model.number="quantity" min="1" max="{{ max(1, (int) ($product['stock'] ?? 0)) }}" class="w-16 text-center border-x border-gray-300 py-2 focus:outline-none rounded-none" aria-label="Quantity">
                        <button @click="if (quantity < {{ max(1, (int) ($product['stock'] ?? 0)) }}) quantity++" type="button" class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition rounded-none">+</button>
                    </div>
                    <button type="button"
                        class="add-to-cart-btn flex-1 bg-primary text-white px-8 py-3 rounded-full font-semibold hover:bg-primary-dark transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                        data-product-id="{{ $product['id'] }}"
                        :data-quantity="quantity"
                        @if(($product['stock'] ?? 0) === 0) disabled @endif>
                        Add to Cart
                    </button>
                </div>

                <div class="flex flex-wrap gap-4 text-sm text-gray-500 border-t border-gray-100 pt-6">
                    <div class="flex items-center gap-2">Free shipping over minimum spend</div>
                    <div class="flex items-center gap-2">30-day guarantee</div>
                    <div class="flex items-center gap-2">Easy returns</div>
                </div>
            </div>
        </div>
    </main>

    <div class="mt-16" x-data="{ activeTab: 'description' }">
        <div class="flex border-b border-gray-200">
            <button @click="activeTab = 'description'" :class="activeTab === 'description' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="px-6 py-3 font-semibold border-b-2 transition rounded-none">Description</button>
            @if (! $isBundle)
                <button @click="activeTab = 'ingredients'" :class="activeTab === 'ingredients' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="px-6 py-3 font-semibold border-b-2 transition rounded-none">Ingredients</button>
                <button @click="activeTab = 'feeding'" :class="activeTab === 'feeding' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="px-6 py-3 font-semibold border-b-2 transition rounded-none">Feeding Guide</button>
            @else
                <button @click="activeTab = 'details'" :class="activeTab === 'details' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="px-6 py-3 font-semibold border-b-2 transition rounded-none">Specifications</button>
            @endif
            <button @click="activeTab = 'shipping'" :class="activeTab === 'shipping' ? 'border-primary text-primary' : 'border-transparent text-gray-500'" class="px-6 py-3 font-semibold border-b-2 transition rounded-none">Shipping & Returns</button>
        </div>
        <div class="py-8 text-gray-600 leading-relaxed">
            <div x-show="activeTab === 'description'">
                {!! $product['description'] !!}
            </div>
            @if (! $isBundle)
                <div x-show="activeTab === 'ingredients'" class="space-y-3">
                    <p class="font-semibold text-gray-900">Ingredients:</p>
                    <p>Deboned Chicken, Chicken Meal, Oatmeal, Barley, Chicken Fat, Flaxseed, Salmon Oil, Pumpkin, Blueberries, Carrots, Vitamins, and Minerals.</p>
                    <p class="font-semibold text-gray-900 pt-2">Guaranteed Analysis:</p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>Crude Protein (min): 28%</div>
                        <div>Crude Fat (min): 16%</div>
                        <div>Crude Fiber (max): 4%</div>
                        <div>Moisture (max): 10%</div>
                    </div>
                </div>
                <div x-show="activeTab === 'feeding'" class="space-y-3">
                    <p>Feed according to your puppy's age and expected adult weight. Adjust to maintain ideal body condition.</p>
                    <div class="grid md:grid-cols-2 gap-2 text-sm">
                        <div>5-10 lbs: 1/2 to 1 cup daily</div>
                        <div>10-20 lbs: 1 to 1 3/4 cups daily</div>
                        <div>20-40 lbs: 1 3/4 to 3 cups daily</div>
                        <div>40+ lbs: 3+ cups daily</div>
                    </div>
                    <p class="text-sm text-gray-500">Always provide fresh water. Transition gradually over 7-10 days when changing diets.</p>
                </div>
            @else
                <div x-show="activeTab === 'details'" class="grid md:grid-cols-2 gap-4">
                    <div><span class="font-semibold text-gray-900">Category:</span> {{ $product['category'] }}</div>
                    <div><span class="font-semibold text-gray-900">Availability:</span> {{ ($product['stock'] ?? 0) > 0 ? 'In stock' : 'Out of stock' }}</div>
                    <div><span class="font-semibold text-gray-900">Product type:</span> Curated for young dogs</div>
                    <div><span class="font-semibold text-gray-900">SKU:</span> PDP-{{ $product['id'] }}</div>
                </div>
            @endif
            <div x-show="activeTab === 'shipping'" class="space-y-4">
                <p><span class="font-semibold text-gray-900">Delivery Fee:</span> {{ $displayPrice['symbol'] }}{{ number_format(\App\Helpers\CurrencyHelper::deliveryFee(), 2) }}</p>
                <p><span class="font-semibold text-gray-900">Delivery Window:</span> 2-5 business days depending on location.</p>
                <p><span class="font-semibold text-gray-900">Returns:</span> Easy returns on unused items under our policy terms.</p>
            </div>
        </div>
    </div>

    <section class="mt-16 pt-12 border-t border-gray-200" aria-labelledby="reviews-heading">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between mb-6">
            <div>
                <h2 id="reviews-heading" class="text-2xl font-bold mb-1">Customer reviews</h2>
                @if ($reviews->isEmpty())
                    <p class="text-gray-600">No published reviews yet — be the first to share your thoughts.</p>
                @else
                    <p class="text-gray-600">{{ $reviews->count() }} {{ $reviews->count() === 1 ? 'review' : 'reviews' }} from buyers</p>
                @endif
            </div>
        </div>

        @include('livewire.partials.product-review-form')

        @if ($reviews->isNotEmpty())
            <ul class="space-y-8 mt-10">
                @foreach ($reviews as $review)
                    <li class="rounded-xl border border-gray-200 p-6 bg-gray-50/50">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="font-semibold text-gray-900">{{ $review->author_name ?? $review->user?->name ?? 'Customer' }}</span>
                            <span class="text-sm text-gray-600" aria-label="Rating: {{ $review->rating }} out of 5">{{ $review->rating }}/5</span>
                            <time class="text-sm text-gray-500" datetime="{{ $review->created_at->toIso8601String() }}">{{ $review->created_at->format('M j, Y') }}</time>
                        </div>
                        @if (filled($review->title))
                            <p class="font-medium text-gray-800 mb-2">{{ $review->title }}</p>
                        @endif
                        <p class="text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                        @if (filled($review->breed) || filled($review->puppy_age_at_review))
                            <p class="text-sm text-gray-500 mt-3">
                                @if (filled($review->breed))
                                    <span>{{ $review->breed }}</span>
                                @endif
                                @if (filled($review->breed) && filled($review->puppy_age_at_review))
                                    <span> · </span>
                                @endif
                                @if (filled($review->puppy_age_at_review))
                                    <span>Puppy was {{ $review->puppy_age_at_review }} weeks old</span>
                                @endif
                            </p>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    @if (! empty($related))
        <section class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">You Might Also Like</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($related as $item)
                    @php $rp = \App\Helpers\CurrencyHelper::formatProductPrice($item); @endphp
                    <article class="rounded-xl overflow-hidden border border-gray-200 bg-white hover:border-primary/30 transition">
                        <a href="{{ route('products.show', $item['slug']) }}" class="block no-underline">
                            <img src="{{ $item['images'][0] ?? '' }}" alt="{{ $item['name'] }}" class="w-full aspect-4/3 object-cover" loading="lazy">
                        </a>
                        <div class="p-4">
                            <a href="{{ route('products.show', $item['slug']) }}" class="block no-underline">
                                <h3 class="font-semibold text-lg text-gray-900">{{ $item['name'] }}</h3>
                            </a>
                            <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ $item['shortDescription'] ?? '' }}</p>
                            <div class="mt-4 flex items-center justify-between gap-3">
                                <span class="font-bold text-primary">{{ $rp['symbol'] }}{{ $rp['formatted'] }}</span>
                                <button type="button" class="add-to-cart-btn px-4 py-2 rounded-full bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition" data-product-id="{{ $item['id'] }}">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
</div>

@push('scripts')
<script>
    window.products = window.products || [];
    @foreach (array_merge([$product], $related) as $pForJs)
    (function () {
        const p = @json($pForJs);
        if (!window.products.some((x) => x.id === p.id)) window.products.push(p);
    })();
    @endforeach
</script>
@endpush
