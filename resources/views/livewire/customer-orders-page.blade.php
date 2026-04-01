<div>
    <h1 class="font-display text-2xl font-bold text-gray-900 mb-2">Order history</h1>
    <p class="text-gray-600 mb-8">Track shipments and reorder your favorites.</p>

    @if ($orders->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-gray-600">
            <p class="mb-4">You don’t have any orders yet.</p>
            <a href="{{ route('products.index') }}" class="inline-flex rounded-full bg-primary text-white font-semibold px-6 py-2.5 hover:bg-primary-dark">Browse products</a>
        </div>
    @else
        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
            <ul class="divide-y divide-gray-100">
                @foreach ($orders as $order)
                    <li class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <span class="font-semibold text-gray-900">Order #{{ $order->id }}</span>
                            <span class="text-gray-500 text-sm ml-2">{{ $order->created_at->format('M j, Y · g:i A') }}</span>
                            <p class="text-sm text-gray-600 mt-1 capitalize">
                                {{ \App\Models\Order::statuses()[$order->status] ?? $order->status }}
                                @if ($order->tracking_number)
                                    · Tracking: {{ $order->tracking_number }}
                                @endif
                            </p>
                            <p class="text-sm font-medium text-gray-900 mt-1">
                                {{ \App\Helpers\CurrencyHelper::symbol() }}{{ number_format((float) $order->total_amount, 2) }}
                                · {{ $order->items_count }} {{ Str::plural('item', $order->items_count) }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('customer.orders.show', $order) }}" class="inline-flex items-center rounded-full border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">View & track</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="mt-6">{{ $orders->links() }}</div>
    @endif
</div>
