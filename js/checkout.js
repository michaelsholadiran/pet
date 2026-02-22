(function() {
    document.addEventListener('DOMContentLoaded', function() {
        var cart = typeof getCart === 'function' ? getCart() : [];
        var productsList = (typeof products !== 'undefined' ? products : null) || (typeof window.products !== 'undefined' ? window.products : []) || [];
        var orderItems = document.getElementById('order-items');
        var total = 0;

        if (!orderItems) return;

        // ----- BEGIN Order summary (cart items, totals, analytics) -----
        var sym = (typeof window.CURRENCY_SYMBOL !== 'undefined' && window.CURRENCY_SYMBOL) ? window.CURRENCY_SYMBOL : '₦';
        cart.forEach(function(item) {
            var product = productsList.find(function(p) { return p.id === item.id; });
            if (product) {
                var unitPrice = typeof getProductPrice === 'function' ? getProductPrice(product) : product.price;
                var itemTotal = unitPrice * item.quantity;
                total += itemTotal;
                var fmt = typeof formatPrice === 'function' ? formatPrice(itemTotal) : itemTotal.toLocaleString('en-NG', { minimumFractionDigits: 2 });
                var imgSrc = (product.images && product.images[0]) ? product.images[0] : '';
                var imgHtml = imgSrc ? '<img src="' + imgSrc + '" alt="" class="order-item-img">' : '';
                orderItems.innerHTML += '<div class="order-item">' + (imgSrc ? '<div class="order-item-thumb">' + imgHtml + '</div>' : '') + '<div class="order-item-details"><span class="order-item-name">' + product.name + ' x ' + item.quantity + '</span><span class="order-item-price">' + sym + fmt + '</span></div></div>';
            }
        });

        var deliveryFee = typeof DELIVERY_FEE !== 'undefined' ? DELIVERY_FEE : (typeof window.DELIVERY_FEE !== 'undefined' ? window.DELIVERY_FEE : 4800);
        var grandTotal = total + deliveryFee;

        var deliveryEl = document.getElementById('delivery-fee');
        var totalEl = document.getElementById('total-price');
        var totalCurrencyEl = document.getElementById('total-currency');
        var fmtTotal = typeof formatPrice === 'function' ? formatPrice(grandTotal) : grandTotal.toLocaleString('en-NG', { minimumFractionDigits: 2 });
        var fmtFee = typeof formatPrice === 'function' ? formatPrice(deliveryFee) : deliveryFee.toLocaleString('en-NG', { minimumFractionDigits: 2 });
        if (deliveryEl) deliveryEl.textContent = sym + fmtFee;
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
                var deliveryFeeVal = typeof DELIVERY_FEE !== 'undefined' ? DELIVERY_FEE : (typeof window.DELIVERY_FEE !== 'undefined' ? window.DELIVERY_FEE : 4800);
                var grandTotalVal = total + deliveryFeeVal;
                if (window.PaystackCheckout && window.PaystackCheckout.run) {
                    window.PaystackCheckout.run(checkoutForm, {
                        cart: cart,
                        productsList: productsList,
                        total: total,
                        deliveryFee: deliveryFeeVal,
                        grandTotal: grandTotalVal
                    });
                } else {
                    alert('Payment system is loading. Please wait a moment and try again.');
                }
            });
        }
        // ----- END Paystack button -----
    });
})();
