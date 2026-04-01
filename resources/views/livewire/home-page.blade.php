<div class="space-y-16 sm:space-y-20 pb-16">
    <section class="px-6 pt-10 sm:pt-14">
        <div class="max-w-[1200px] mx-auto rounded-3xl overflow-hidden border border-gray-200 bg-white">
            <div class="grid grid-cols-1 lg:grid-cols-2 items-stretch">
                <div class="p-8 sm:p-10 lg:p-12 flex flex-col justify-center">
                    <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight mb-4">Welcome to Puppiary</h1>
                    <p class="text-xl text-gray-700 mb-2">Everything your new puppy needs — in one simple box.</p>
                    <p class="text-lg text-gray-600 mb-8">No guesswork. No overwhelm.</p>
                    <div>
                        <a href="{{ route('starter-kit') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-full bg-primary text-white font-semibold text-lg no-underline hover:bg-primary-dark transition">
                            Shop the Starter Kit
                        </a>
                    </div>
                </div>
                <div class="min-h-[360px]">
                    <img src="{{ asset('images/puppiary-homepage-promotional-banner.webp') }}" alt="A calm puppy resting at home" class="w-full h-full object-cover" loading="eager" />
                </div>
            </div>
        </div>
    </section>

    <section class="px-6">
        <div class="max-w-[1200px] mx-auto">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Simple Choices</h2>
            <p class="text-gray-600 mb-8 max-w-2xl">We know bringing home a puppy can feel confusing. Here's how we can help you:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-7">
                    <p class="text-sm font-semibold text-primary mb-2">1. Just got my puppy</p>
                    <p class="text-gray-700 mb-5">Everything you need for the first few weeks.</p>
                    <a href="{{ route('starter-kit') }}" class="inline-flex items-center font-semibold text-primary no-underline hover:underline">Shop Starter Kit →</a>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-7">
                    <p class="text-sm font-semibold text-primary mb-2">2. My puppy is growing</p>
                    <p class="text-gray-700 mb-5">Food, treats and essentials for the next stage.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center font-semibold text-primary no-underline hover:underline">Browse Products →</a>
                </div>
            </div>
        </div>
    </section>

    <section class="px-6">
        <div class="max-w-[1200px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div class="rounded-2xl overflow-hidden border border-gray-200 bg-white">
                <img src="{{ asset('images/puppy-wearing-our-no-pull-harness.webp') }}" alt="Puppiary complete starter kit" class="w-full h-full object-cover min-h-[320px]" loading="lazy" />
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Our Complete Starter Kit</h2>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">The Complete Starter Kit</h3>
                <p class="text-gray-700 mb-6">Everything for a calm and healthy start.</p>
                <ul class="space-y-3 mb-7">
                    <li class="flex items-start gap-3 text-gray-800"><span class="text-primary font-bold">•</span> Safe puppy food</li>
                    <li class="flex items-start gap-3 text-gray-800"><span class="text-primary font-bold">•</span> Gentle toys and grooming items</li>
                    <li class="flex items-start gap-3 text-gray-800"><span class="text-primary font-bold">•</span> Easy step-by-step guide</li>
                </ul>
                <p class="text-gray-700 mb-6">You'll feel more confident from day one.</p>
                <a href="{{ route('starter-kit') }}" class="inline-flex items-center justify-center px-7 py-3 rounded-full bg-primary text-white font-semibold no-underline hover:bg-primary-dark transition">
                    Shop the Starter Kit
                </a>
            </div>
        </div>
    </section>

    <section class="px-6">
        <div class="max-w-[1200px] mx-auto">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">Real Words from Other Puppy Parents</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-7">
                    <p class="text-lg text-gray-800 mb-3">"I'm 57 and this made everything so much easier."</p>
                    <p class="text-sm font-semibold text-gray-600">— Susan</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-7">
                    <p class="text-lg text-gray-800 mb-3">"Simple. Clear. I knew exactly what to do."</p>
                    <p class="text-sm font-semibold text-gray-600">— David, 61</p>
                </div>
            </div>
        </div>
    </section>

    <section class="px-6">
        <div class="max-w-[1200px] mx-auto">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">Help When You Need It</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('guide.show', ['slug' => 'getting-started-with-your-new-puppy']) }}" class="rounded-2xl border border-gray-200 bg-white p-7 no-underline hover:border-primary/40 transition">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Your First 30 Days Guide</h3>
                    <p class="text-gray-600 mb-4">A simple daily routine — easy to follow.</p>
                    <span class="font-semibold text-primary">Read the Guide →</span>
                </a>
                <a href="{{ route('guide.show', ['slug' => 'understanding-puppy-nutrition-a-complete-guide']) }}" class="rounded-2xl border border-gray-200 bg-white p-7 no-underline hover:border-primary/40 transition">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Puppy Feeding Guide</h3>
                    <p class="text-gray-600 mb-4">What to feed and when.</p>
                    <span class="font-semibold text-primary">Read the Guide →</span>
                </a>
            </div>
        </div>
    </section>
</div>
