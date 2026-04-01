<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    <div class="grid lg:grid-cols-3 gap-8">
        <form id="checkout-form" class="lg:col-span-2 space-y-6">
            <section class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Shipping Information</h2>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" id="email" name="email" required class="w-full border border-gray-300 rounded-full px-4 py-2">
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                        <input type="text" id="first_name" name="first_name" required class="w-full border border-gray-300 rounded-full px-4 py-2">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" required class="w-full border border-gray-300 rounded-full px-4 py-2">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required class="w-full border border-gray-300 rounded-full px-4 py-2">
                </div>

                <div class="mb-4">
                    <label for="address1" class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                    <input type="text" id="address1" name="address1" required class="w-full border border-gray-300 rounded-full px-4 py-2">
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                        <input type="text" id="city" name="city" required class="w-full border border-gray-300 rounded-full px-4 py-2">
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                        <select id="state" name="state" required class="w-full border border-gray-300 rounded-full px-4 py-2">
                            <option value="">Select</option>
                            @foreach($states as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">{{ $country === 'Nigeria' ? 'Postal code' : 'ZIP code' }} *</label>
                        <input type="text" id="postal_code" name="postal_code" required placeholder="{{ $country === 'Nigeria' ? 'e.g. 100001' : 'e.g. 90210' }}" class="w-full border border-gray-300 rounded-full px-4 py-2">
                    </div>
                </div>

                <input type="hidden" id="country" name="country" value="{{ $country }}">
                <input type="hidden" id="fullname" name="fullname">
            </section>
        </form>

        <aside class="lg:col-span-1">
            <div class="sticky top-24 overflow-hidden rounded-2xl border border-gray-200 bg-white">
                <div class="border-b border-gray-100 bg-gray-50 px-5 py-4">
                    <h2 class="text-lg font-bold text-gray-900">Order summary</h2>
                    <p id="order-summary-count" class="mt-1 text-xs text-gray-500"></p>
                </div>

                <div id="order-items" class="max-h-[min(22rem,55vh)] divide-y divide-gray-100 overflow-y-auto overscroll-contain px-5"></div>

                <div class="space-y-3 border-t border-gray-200 bg-gray-50 px-5 py-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span id="order-subtotal" class="font-medium tabular-nums text-gray-900">—</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Shipping</span>
                        <span id="delivery-fee" class="font-medium tabular-nums text-gray-900">—</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-3 text-lg font-bold">
                        <span class="text-gray-900">Total</span>
                        <span class="tabular-nums text-primary"><span id="total-currency"></span><span id="total-price">0.00</span></span>
                    </div>
                </div>

                <div class="px-5 pb-5 pt-2">
                    <button type="button" id="place-order-btn" class="w-full rounded-full bg-primary py-3.5 text-base font-semibold text-white transition hover:bg-primary-dark">
                        Place Order
                    </button>
                    <div class="mt-4 space-y-1 text-center text-xs text-gray-500">
                        <p>30-day happiness guarantee</p>
                        <p>Shipping details will be used for fulfillment</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</main>

@push('scripts')
<script>
    window.products = @json(\App\Data\ProductsData::all());
    window.PAYPAL_API = "{{ url('/api/paypal') }}";
    window.PAYSTACK_PUBLIC_KEY = @json(config('services.paystack.key') ?? env('PAYSTACK_PUBLIC_KEY') ?? 'pk_live_b502bc43c6a41a29f804bacde50cf34f9b35502a');
    function clearCart() { localStorage.removeItem('puppiary-cart'); if (typeof updateCartCounter === 'function') updateCartCounter(); }
</script>
<script src="{{ asset('js/checkout.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('checkout-form');
        var placeOrderBtn = document.getElementById('place-order-btn');
        if (!form || !placeOrderBtn) return;

        placeOrderBtn.addEventListener('click', function () {
            var requiredIds = ['email', 'first_name', 'last_name', 'phone', 'address1', 'city', 'state', 'postal_code'];
            var missing = requiredIds.some(function (id) {
                var el = document.getElementById(id);
                return !el || !String(el.value || '').trim();
            });
            if (missing) {
                alert('Please fill in all shipping fields.');
                return;
            }

            var email = document.getElementById('email').value.trim();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address.');
                return;
            }

            var firstName = document.getElementById('first_name').value.trim();
            var lastName = document.getElementById('last_name').value.trim();
            var fullname = (firstName + ' ' + lastName).trim();
            document.getElementById('fullname').value = fullname;

            var total = Number((document.getElementById('total-price')?.textContent || '0').replace(/,/g, ''));
            var cart = typeof getCart === 'function' ? getCart() : [];
            var orderData = {
                transaction_id: 'MANUAL_' + Date.now(),
                cart: cart,
                grand_total: total,
                email: email,
                fullname: fullname,
                phone: document.getElementById('phone').value.trim(),
                address1: document.getElementById('address1').value.trim(),
                state: document.getElementById('state').value,
                city: document.getElementById('city').value.trim(),
                postal_code: document.getElementById('postal_code').value.trim(),
                country: document.getElementById('country').value
            };

            localStorage.setItem('puppiary-order-data', JSON.stringify(orderData));
            clearCart();
            window.location.href = '/success?ref=' + encodeURIComponent(orderData.transaction_id);
        });
    });
</script>
@endpush
