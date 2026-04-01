<div x-data="{ activeCategory: 'all' }">
    <section class="bg-linear-to-r from-primary to-primary-dark text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Puppy Guide</h1>
            <p class="text-xl text-white/90">Everything you need to know about raising a happy, healthy puppy. Step-by-step guides for new puppy parents.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-wrap justify-center gap-3">
            <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200'" class="px-5 py-2 rounded-full font-medium transition border">All Guides</button>
            <button @click="activeCategory = 'potty'" :class="activeCategory === 'potty' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200'" class="px-5 py-2 rounded-full font-medium transition border">Potty Training</button>
            <button @click="activeCategory = 'feeding'" :class="activeCategory === 'feeding' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200'" class="px-5 py-2 rounded-full font-medium transition border">Feeding</button>
            <button @click="activeCategory = 'training'" :class="activeCategory === 'training' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200'" class="px-5 py-2 rounded-full font-medium transition border">Training</button>
            <button @click="activeCategory = 'health'" :class="activeCategory === 'health' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200'" class="px-5 py-2 rounded-full font-medium transition border">Health & Wellness</button>
            <button @click="activeCategory = 'social'" :class="activeCategory === 'social' ? 'bg-primary text-white border-primary' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200'" class="px-5 py-2 rounded-full font-medium transition border">Socialization</button>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($articles as $article)
                @php
                    $text = \Illuminate\Support\Str::lower($article->title.' '.strip_tags($article->content));
                    $cat = 'health';
                    if (\Illuminate\Support\Str::contains($text, ['potty', 'housebreak', 'crate'])) $cat = 'potty';
                    elseif (\Illuminate\Support\Str::contains($text, ['feed', 'nutrition', 'food'])) $cat = 'feeding';
                    elseif (\Illuminate\Support\Str::contains($text, ['train', 'command', 'bite'])) $cat = 'training';
                    elseif (\Illuminate\Support\Str::contains($text, ['social', 'playdate'])) $cat = 'social';
                    elseif (\Illuminate\Support\Str::contains($text, ['vet', 'health', 'wellness'])) $cat = 'health';
                    $badge = [
                        'potty' => 'Potty Training',
                        'feeding' => 'Feeding',
                        'training' => 'Training',
                        'health' => 'Health',
                        'social' => 'Socialization',
                    ][$cat];
                    $badgeClass = [
                        'potty' => 'bg-green-100 text-green-700',
                        'feeding' => 'bg-amber-100 text-amber-700',
                        'training' => 'bg-blue-100 text-blue-700',
                        'health' => 'bg-red-100 text-red-700',
                        'social' => 'bg-purple-100 text-purple-700',
                    ][$cat];
                @endphp
                <article x-show="activeCategory === 'all' || activeCategory === '{{ $cat }}'" class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:border-primary/30 transition">
                    @if($article->featured_image)
                        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-100"></div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="{{ $badgeClass }} text-xs font-semibold px-2 py-1 rounded-full">{{ $badge }}</span>
                            <span class="text-xs text-gray-400">{{ max(3, ceil(str_word_count(strip_tags($article->content)) / 180)) }} min read</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $article->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 140) }}</p>
                        <a href="{{ route('guide.show', $article->slug) }}" class="inline-flex items-center gap-2 text-primary font-semibold hover:text-primary-dark group no-underline">
                            Read More <span class="group-hover:translate-x-1 transition">→</span>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600 mb-4">Guides are coming soon. In the meantime, check out our FAQ.</p>
                    <a href="{{ route('faq') }}" class="inline-block px-8 py-3 rounded-full font-semibold bg-primary text-white no-underline">View FAQ</a>
                </div>
            @endforelse
        </div>

        <div class="mt-12 bg-primary/10 rounded-2xl p-8 text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Get Weekly Puppy Tips</h3>
            <p class="text-gray-600 mb-4">Sign up for updates and practical puppy-care guidance.</p>
            <div class="flex max-w-md mx-auto gap-3">
                <input type="email" placeholder="Your email address" class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:ring-2 focus:ring-primary">
                <button type="button" class="bg-primary text-white px-6 py-2 rounded-full font-semibold hover:bg-primary-dark transition">Subscribe</button>
            </div>
        </div>
    </main>
</div>
