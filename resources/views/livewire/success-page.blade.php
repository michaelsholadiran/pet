<div class="min-h-[70vh] flex items-center justify-center py-16 px-8">
    <div class="max-w-[720px] mx-auto text-center" role="status" aria-live="polite">
        <h1 class="text-4xl font-bold mb-4">Order Confirmed</h1>
        <p class="text-xl text-gray-600 mb-2">Thank you for your purchase. Your payment has been received.</p>
        <p class="mb-8">Reference: <strong id="order-ref">—</strong></p>
        <div class="flex gap-4 justify-center flex-wrap">
            <a href="{{ route('home') }}" class="inline-block px-8 py-4 rounded-full bg-primary text-white font-semibold no-underline hover:bg-primary-dark">Back to Home</a>
            <a href="{{ route('products.index') }}" class="inline-block px-8 py-4 rounded-full border-2 border-primary text-primary font-semibold no-underline hover:bg-primary hover:text-white">Continue Shopping</a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var params = new URLSearchParams(window.location.search);
    var ref = params.get('ref');
    if (ref) {
        var el = document.getElementById('order-ref');
        if (el) el.textContent = ref;
    }
    var orderDataStr = localStorage.getItem('puppiary-order-data');
    if (orderDataStr && typeof trackPurchase === 'function') {
        try {
            var orderData = JSON.parse(orderDataStr);
            trackPurchase(orderData.transaction_id || ref, orderData.cart || [], orderData.total || 0, orderData.delivery_fee || 0, orderData.email || '');
            localStorage.removeItem('puppiary-order-data');
        } catch (e) { console.error('Error tracking purchase:', e); }
    }
    if (typeof confetti === 'function') {
        var duration = 1500, end = Date.now() + duration;
        (function frame() {
            confetti({ particleCount: 60, spread: 70, origin: { y: 0.6 } });
            if (Date.now() < end) requestAnimationFrame(frame);
        })();
        confetti({ particleCount: 80, angle: 60, spread: 55, origin: { x: 0 } });
        confetti({ particleCount: 80, angle: 120, spread: 55, origin: { x: 1 } });
    }
});
</script>
@endpush
