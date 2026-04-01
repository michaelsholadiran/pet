<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>
    <div id="cart-content"><!-- Rendered by shop.js renderCart() --></div>
</div>

@push('scripts')
<script>
    window.products = @json(\App\Data\ProductsData::all());
    (function initCart() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCart);
            return;
        }
        const el = document.getElementById('cart-content');
        if (el && typeof renderCart === 'function') {
            renderCart();
            let t;
            window.addEventListener('resize', () => { clearTimeout(t); t = setTimeout(renderCart, 250); });
        }
    })();
</script>
@endpush
