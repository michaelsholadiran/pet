(function() {
    document.addEventListener('DOMContentLoaded', function() {
        var cart = typeof getCart === 'function' ? getCart() : [];
        var productsList = (typeof products !== 'undefined' ? products : null) || (typeof window.products !== 'undefined' ? window.products : []) || [];
        var orderItems = document.getElementById('order-items');
        var total = 0;

        if (!orderItems) return;

        function escHtml(s) {
            return String(s == null ? '' : s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function escAttr(s) {
            return escHtml(s).replace(/'/g, '&#39;');
        }

        // ----- BEGIN Order summary (cart items, totals, analytics) -----
        var sym = (typeof window.CURRENCY_SYMBOL !== 'undefined' && window.CURRENCY_SYMBOL) ? window.CURRENCY_SYMBOL : '₦';
        var isUsd = typeof window.CURRENCY !== 'undefined' && window.CURRENCY === 'USD';
        var freeShippingThreshold = isUsd ? 100 : 100000;
        var deliveryFeeBase = typeof window.DELIVERY_FEE !== 'undefined' ? window.DELIVERY_FEE : (typeof DELIVERY_FEE !== 'undefined' ? DELIVERY_FEE : 4800);

        orderItems.innerHTML = '';

        var qtyCount = 0;
        cart.forEach(function(item) {
            qtyCount += item.quantity;
            var product = productsList.find(function(p) { return p.id === item.id; });
            if (!product) return;
            var unitPrice = typeof getProductPrice === 'function' ? getProductPrice(product) : product.price;
            var itemTotal = unitPrice * item.quantity;
            total += itemTotal;
            var fmtLine = typeof formatPrice === 'function' ? formatPrice(itemTotal) : itemTotal.toLocaleString('en-NG', { minimumFractionDigits: 2 });
            var fmtEach = typeof formatPrice === 'function' ? formatPrice(unitPrice) : unitPrice.toLocaleString('en-NG', { minimumFractionDigits: 2 });
            var imgSrc = (product.images && product.images[0]) ? product.images[0] : '';
            var thumb = imgSrc
                ? '<div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl border border-gray-100 bg-gray-50"><img src="' + escAttr(imgSrc) + '" alt="" class="h-full w-full object-cover" loading="lazy"></div>'
                : '<div class="h-16 w-16 shrink-0 rounded-xl border border-dashed border-gray-200 bg-gray-50"></div>';
            orderItems.innerHTML +=
                '<div class="flex gap-3 py-4">' +
                thumb +
                '<div class="min-w-0 flex-1 pt-0.5">' +
                '<p class="text-sm font-semibold leading-snug text-gray-900">' + escHtml(product.name) + '</p>' +
                '<p class="mt-1 text-xs text-gray-500">Qty ' + item.quantity + '</p>' +
                '</div>' +
                '<div class="shrink-0 text-right">' +
                '<p class="text-sm font-semibold text-gray-900">' + sym + fmtLine + '</p>' +
                '<p class="mt-0.5 text-xs text-gray-400">' + sym + fmtEach + ' each</p>' +
                '</div>' +
                '</div>';
        });

        var countEl = document.getElementById('order-summary-count');
        var subtotalEl = document.getElementById('order-subtotal');

        if (cart.length === 0 || total === 0) {
            orderItems.innerHTML =
                '<div class="py-10 text-center">' +
                '<p class="text-sm text-gray-600">Your cart is empty.</p>' +
                '<a href="/products" class="mt-3 inline-block text-sm font-semibold text-primary no-underline hover:underline">Continue shopping</a>' +
                '</div>';
            if (countEl) countEl.textContent = '';
            if (subtotalEl) subtotalEl.textContent = '—';
        } else if (countEl) {
            countEl.textContent = qtyCount === 1 ? '1 item' : qtyCount + ' items';
        }

        var fmtSub = typeof formatPrice === 'function' ? formatPrice(total) : total.toLocaleString('en-NG', { minimumFractionDigits: 2 });
        if (subtotalEl && cart.length > 0 && total > 0) subtotalEl.textContent = sym + fmtSub;

        var deliveryFee;
        var grandTotal;
        if (cart.length === 0 || total === 0) {
            deliveryFee = 0;
            grandTotal = 0;
        } else {
            deliveryFee = total > freeShippingThreshold ? 0 : deliveryFeeBase;
            grandTotal = total + deliveryFee;
        }

        var deliveryEl = document.getElementById('delivery-fee');
        var totalEl = document.getElementById('total-price');
        var totalCurrencyEl = document.getElementById('total-currency');
        var fmtTotal = typeof formatPrice === 'function' ? formatPrice(grandTotal) : grandTotal.toLocaleString('en-NG', { minimumFractionDigits: 2 });
        var fmtFee = typeof formatPrice === 'function' ? formatPrice(deliveryFee) : deliveryFee.toLocaleString('en-NG', { minimumFractionDigits: 2 });
        if (deliveryEl) {
            if (cart.length === 0 || total === 0) {
                deliveryEl.textContent = '—';
                deliveryEl.classList.remove('text-green-600', 'font-semibold');
            } else {
                deliveryEl.textContent = deliveryFee === 0 ? 'Free' : sym + fmtFee;
                deliveryEl.classList.toggle('text-green-600', deliveryFee === 0);
                deliveryEl.classList.toggle('font-semibold', deliveryFee === 0);
            }
        }
        if (totalEl) totalEl.textContent = fmtTotal;
        if (totalCurrencyEl) totalCurrencyEl.textContent = sym;

        if (cart.length > 0 && typeof trackBeginCheckout === 'function') {
            trackBeginCheckout(cart, total, deliveryFee);
        }
        // ----- END Order summary (cart items, totals, analytics) -----

        var checkoutForm = document.getElementById('checkout-form');
        if (!checkoutForm) return;

        // ----- BEGIN Shipping fields localStorage (restore + save on input) -----
        var SHIPPING_STORAGE_KEY = 'puppiary-checkout-shipping';
        var shippingFields = [
            { id: 'email', key: 'email' },
            { id: 'phone', key: 'phone' },
            { id: 'fullname', key: 'fullname' },
            { id: 'address1', key: 'address1' },
            { id: 'state', key: 'state' },
            { id: 'postal_code', key: 'postal_code' }
        ];

        try {
            var saved = localStorage.getItem(SHIPPING_STORAGE_KEY);
            if (saved) {
                var data = JSON.parse(saved);
                shippingFields.forEach(function(f) {
                    var el = document.getElementById(f.id);
                    if (!el || data[f.key] == null || data[f.key] === '') return;
                    if (f.id === 'state') {
                        var opts = el.options;
                        for (var i = 0; i < opts.length; i++) {
                            if (opts[i].value === data[f.key]) { el.value = data[f.key]; break; }
                        }
                    } else {
                        el.value = data[f.key];
                    }
                });
            }
        } catch (e) {}

        function saveShippingToStorage() {
            var data = {};
            shippingFields.forEach(function(f) {
                var el = document.getElementById(f.id);
                data[f.key] = el ? el.value : '';
            });
            try {
                localStorage.setItem(SHIPPING_STORAGE_KEY, JSON.stringify(data));
            } catch (e) {}
        }

        shippingFields.forEach(function(f) {
            var el = document.getElementById(f.id);
            if (el) {
                el.addEventListener('input', saveShippingToStorage);
                el.addEventListener('change', saveShippingToStorage);
            }
        });
        // ----- END Shipping fields localStorage (restore + save on input) -----

        var paypalContainer = document.getElementById('paypal-button-container');
        var btnPaystack = document.getElementById('btn-paystack');

        // ----- BEGIN PayPal init -----
        if (window.PaypalCheckout && window.PaypalCheckout.init) {
            window.PaypalCheckout.init({
                paypalContainer: paypalContainer,
                checkoutForm: checkoutForm,
                checkoutSubmitBtn: null,
                cart: cart,
                productsList: productsList,
                total: total,
                deliveryFee: deliveryFee,
                grandTotal: grandTotal
            });
        }
        // ----- END PayPal init -----

        // ----- BEGIN Paystack button -----
        // Paystack is NGN-only: hide for USD (other countries use PayPal)
        if (btnPaystack && window.CURRENCY === 'USD') {
            btnPaystack.style.display = 'none';
        }
        if (btnPaystack) {
            btnPaystack.addEventListener('click', function() {
                if (window.CURRENCY === 'USD') return; // Paystack is NGN-only
                if (window.PaystackCheckout && window.PaystackCheckout.run) {
                    window.PaystackCheckout.run(checkoutForm, {
                        cart: cart,
                        productsList: productsList,
                        total: total,
                        deliveryFee: deliveryFee,
                        grandTotal: grandTotal
                    });
                } else {
                    alert('Payment system is loading. Please wait a moment and try again.');
                }
            });
        }
        // ----- END Paystack button -----
    });
})();
