<div class="max-w-[1200px] mx-auto px-8 py-8" x-data="{ open: null }">
    <h1 class="text-4xl font-bold mb-4">Frequently Asked Questions</h1>
    <p class="text-lg text-gray-600 mb-8">Here are the most common questions our new puppy parents ask. If you don't see your answer here, please contact us!</p>
    <div class="space-y-6">
        @foreach([
            ['q' => 'Q: Do you deliver nationwide?', 'a' => 'Yes! We proudly deliver our safe, quality supplies anywhere in Nigeria. Our standard delivery time is 1–4 working days.'],
            ['q' => 'Q: Can I pay on delivery (PoD)?', 'a' => 'We offer Cash on Delivery (PoD) in many major metropolitan areas, including Lagos, Abuja, and Port Harcourt.'],
            ['q' => 'Q: What if my puppy doesn\'t like the product?', 'a' => 'We offer a 100% Puppy-Approved Money-Back Guarantee. If you or your pup aren\'t completely satisfied within 7 days of delivery, we guarantee a full refund or hassle-free exchange. Visit our <a href="'.route('return-policy').'" class="text-primary underline">Returns page</a> for instructions.'],
            ['q' => 'Q: Are your toys non-toxic and safe for heavy chewers?', 'a' => 'Yes—safety is our non-negotiable promise. All Puppiary toys are made from non-toxic, pet-safe materials and are rigorously tested for durability.'],
        ] as $i => $faq)
        <div class="border border-gray-200 rounded-xl overflow-hidden">
            <button @click="open = open === {{ $i }} ? null : {{ $i }}" class="w-full px-6 py-4 text-left font-semibold flex justify-between items-center">
                <span>{{ $faq['q'] }}</span>
                <span x-text="open === {{ $i }} ? '−' : '+'"></span>
            </button>
            <div x-show="open === {{ $i }}" x-transition class="px-6 pb-4">
                <p class="text-gray-600">{!! $faq['a'] !!}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
