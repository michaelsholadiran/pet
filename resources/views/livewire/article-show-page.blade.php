<style>
    .guide-prose {
        line-height: 1.8;
    }
    .guide-prose h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #111827;
    }
    .guide-prose h3 {
        font-size: 1.4rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: #1f2937;
    }
    .guide-prose p {
        margin-bottom: 1.25rem;
        color: #4b5563;
    }
    .guide-prose ul, .guide-prose ol {
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }
    .guide-prose li {
        margin-bottom: 0.5rem;
        color: #4b5563;
    }
</style>

<div>
    <section class="bg-linear-to-r from-primary to-violet-600 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">{{ $categoryName }}</span>
                <span class="text-white/80 text-sm">{{ $readMinutes }} min read</span>
            </div>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">{{ $article->title }}</h1>
            <p class="text-lg md:text-xl text-white/90 max-w-2xl">{{ $excerpt }}</p>
        </div>
    </section>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid lg:grid-cols-4 gap-8">
            <div class="lg:col-span-3">
                <article class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 guide-prose">
                    @if (count($tableOfContents))
                        <div class="bg-gray-50 rounded-xl p-5 mb-8">
                            <h3 class="font-bold text-gray-900 mb-3 text-base">In this guide</h3>
                            <ul class="space-y-1 text-sm list-none pl-0">
                                @foreach ($tableOfContents as $index => $item)
                                    <li>
                                        <a href="#{{ $item['id'] }}" class="text-primary hover:underline">
                                            {{ $index + 1 }}. {{ $item['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($article->featured_image)
                        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full rounded-xl mb-8">
                    @endif

                    {!! $renderedContent !!}
                </article>

                @if($relatedArticles->isNotEmpty())
                    <section class="mt-12">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">You Might Also Like</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($relatedArticles as $relatedArticle)
                                <a href="{{ route('guide.show', $relatedArticle->slug) }}" class="bg-white border border-gray-200 rounded-xl p-4 hover:border-primary/30 transition no-underline">
                                    <span class="text-sm text-primary font-medium">{{ $relatedArticle->category?->name ?? 'Guide' }}</span>
                                    <h4 class="font-semibold text-gray-900 mt-1">{{ $relatedArticle->title }}</h4>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-primary/10 rounded-xl p-5">
                        <h3 class="font-bold text-gray-900 mb-3">Quick Tips</h3>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>Follow one routine every day.</li>
                            <li>Reward desired behavior quickly.</li>
                            <li>Train with patience and consistency.</li>
                            <li>Track progress week by week.</li>
                        </ul>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <h3 class="font-bold text-gray-900 mb-3">Explore More</h3>
                        <a href="{{ route('puppy-guide') }}" class="inline-flex items-center text-primary font-medium hover:underline">
                            Back to all guides
                        </a>
                    </div>

                    <div class="bg-gray-900 text-white rounded-xl p-5">
                        <h3 class="font-bold mb-2">Get Weekly Tips</h3>
                        <p class="text-sm text-gray-300 mb-3">Expert puppy advice delivered to your inbox.</p>
                        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center w-full bg-primary text-white py-2 rounded-full text-sm font-medium hover:bg-primary-dark no-underline">
                            Contact our team
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </main>
</div>
