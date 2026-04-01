<div>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 mb-8 flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-primary/15 flex items-center justify-center text-primary text-xl font-display font-bold shrink-0">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-display font-bold text-gray-900">Hi, {{ auth()->user()->name }}</h1>
            <p class="text-gray-600 truncate">{{ auth()->user()->email }}</p>
            <div class="flex flex-wrap gap-3 mt-3">
                <a href="{{ route('customer.account') }}" class="text-sm font-semibold text-primary hover:underline">Edit profile</a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('customer.account') }}#password" class="text-sm font-semibold text-primary hover:underline">Change password</a>
                <span class="text-gray-300">|</span>
                <form method="post" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-red-700 hover:underline">Log out</button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
        <a href="{{ route('customer.orders') }}" class="rounded-xl border border-gray-200 bg-white p-5 hover:border-primary/40 transition">
            <p class="text-sm text-gray-500">Orders</p>
            <p class="text-2xl font-bold text-gray-900">{{ $ordersCount }}</p>
            <p class="text-sm text-primary font-medium mt-2">View history →</p>
        </a>
        <a href="{{ route('customer.puppies') }}" class="rounded-xl border border-gray-200 bg-white p-5 hover:border-primary/40 transition">
            <p class="text-sm text-gray-500">Puppies</p>
            <p class="text-2xl font-bold text-gray-900">{{ $puppiesCount }}</p>
            <p class="text-sm text-primary font-medium mt-2">Manage profiles →</p>
        </a>
        <a href="{{ route('customer.reviews') }}" class="rounded-xl border border-gray-200 bg-white p-5 hover:border-primary/40 transition">
            <p class="text-sm text-gray-500">Reviews</p>
            <p class="text-2xl font-bold text-gray-900">{{ $reviewsCount }}</p>
            <p class="text-sm text-primary font-medium mt-2">View & edit →</p>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="font-display text-lg font-bold text-gray-900 mb-4">Recent orders</h2>
            @if ($recentOrders->isEmpty())
                <p class="text-gray-600 text-sm">No orders yet. <a href="{{ route('products.index') }}" class="text-primary font-semibold hover:underline">Start shopping</a></p>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($recentOrders as $order)
                        <li class="py-3 flex justify-between gap-3 text-sm">
                            <div>
                                <span class="font-medium text-gray-900">#{{ $order->id }}</span>
                                <span class="text-gray-500 ml-2">{{ $order->created_at->format('M j, Y') }}</span>
                                <p class="text-gray-600 capitalize">{{ $order->status }} · {{ $order->payment_status ?? '—' }}</p>
                            </div>
                            <a href="{{ route('customer.orders.show', $order) }}" class="shrink-0 text-primary font-semibold hover:underline">Details</a>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('customer.orders') }}" class="inline-block mt-4 text-sm font-semibold text-primary hover:underline">All orders</a>
            @endif
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="font-display text-lg font-bold text-gray-900 mb-4">Learning hub</h2>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('puppy-guide') }}" class="text-primary font-medium hover:underline">First-time puppy guide</a></li>
                <li><a href="{{ route('guide.show', ['slug' => 'understanding-puppy-nutrition-a-complete-guide']) }}" class="text-primary font-medium hover:underline">Feeding guide</a></li>
                <li><a href="{{ route('guide.show', ['slug' => 'crate-training-made-easy']) }}" class="text-primary font-medium hover:underline">Training tips</a></li>
                <li><a href="{{ route('guide.show', ['slug' => 'puppy-potty-training-101']) }}" class="text-primary font-medium hover:underline">Potty training 101</a></li>
                <li><a href="{{ route('puppy-guide') }}" class="text-primary font-medium hover:underline">All articles</a></li>
                <li><a href="{{ route('products.category', ['category' => 'food']) }}" class="text-primary font-medium hover:underline">Shop feeding essentials</a></li>
                <li><a href="{{ route('faq') }}" class="text-primary font-medium hover:underline">FAQ</a></li>
            </ul>
            <h3 class="font-semibold text-gray-900 mt-6 mb-2">Support</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li><a href="{{ route('contact') }}" class="text-primary font-medium hover:underline">Contact us</a> — email, phone, or message</li>
                <li class="text-xs text-gray-500">Payment methods: cards and wallets are entered securely at checkout; we don’t store full card numbers.</li>
            </ul>
        </section>
    </div>
</div>
