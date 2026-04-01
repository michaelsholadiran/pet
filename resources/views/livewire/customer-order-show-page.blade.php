<div>
    <a href="{{ route('customer.orders') }}" class="text-sm font-semibold text-primary hover:underline mb-4 inline-block">← All orders</a>

    <div class="rounded-xl border border-gray-200 bg-white p-6 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                <p class="text-gray-600 mt-1">{{ $order->created_at->format('l, F j, Y · g:i A') }}</p>
                <p class="mt-2 text-sm">
                    <span class="font-semibold text-gray-900">{{ \App\Models\Order::statuses()[$order->status] ?? ucfirst($order->status) }}</span>
                    @if ($order->tracking_number)
                        <span class="text-gray-600"> · Tracking: {{ $order->tracking_number }}</span>
                    @endif
                </p>
                @if (filled($order->tracking_url))
                    <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener" class="inline-block mt-2 text-sm font-semibold text-primary hover:underline">Open carrier tracking</a>
                @endif
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Total</p>
                <p class="text-xl font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::symbol() }}{{ number_format((float) $order->total_amount, 2) }}</p>
            </div>
        </div>

        @if (count($reorderLines) > 0)
            <div class="mt-6 pt-6 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">
                <button type="button"
                    class="inline-flex justify-center rounded-full bg-primary text-white font-semibold px-6 py-2.5 hover:bg-primary-dark transition"
                    data-puppiary-reorder="{{ e(json_encode($reorderLines)) }}">
                    Reorder these items
                </button>
                @if ($reorderSkipped > 0)
                    <p class="text-sm text-amber-800 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">
                        {{ $reorderSkipped }} {{ Str::plural('item', $reorderSkipped) }} unavailable (inactive or out of stock) and were skipped.
                    </p>
                @endif
            </div>
        @else
            <p class="mt-6 pt-6 border-t border-gray-100 text-sm text-gray-600">None of these items are available to reorder right now.</p>
        @endif
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 mb-8">
        <h2 class="font-display text-lg font-bold text-gray-900 mb-4">Items</h2>
        <ul class="divide-y divide-gray-100">
            @foreach ($order->items as $item)
                <li class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                        @if ($item->product)
                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-sm text-primary font-medium hover:underline">View product</a>
                        @endif
                    </div>
                    <div class="text-sm text-gray-600 sm:text-right">
                        <p>Qty {{ $item->quantity }}</p>
                        <p>{{ \App\Helpers\CurrencyHelper::symbol() }}{{ number_format((float) $item->price, 2) }} each</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6">
        <h2 class="font-display text-lg font-bold text-gray-900 mb-4">Shipping & contact</h2>
        <dl class="grid gap-3 text-sm">
            <div>
                <dt class="text-gray-500">Name</dt>
                <dd class="font-medium text-gray-900">{{ $order->fullname ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Email</dt>
                <dd class="font-medium text-gray-900">{{ $order->email ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Phone</dt>
                <dd class="font-medium text-gray-900">{{ $order->phone ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Address</dt>
                <dd class="font-medium text-gray-900 whitespace-pre-line">{{ $order->shipping_address ? $order->shipping_address : '—' }}</dd>
            </div>
        </dl>
    </div>

</div>
