(function() {
    var PAYSTACK_PUBLIC_KEY = 'pk_live_b502bc43c6a41a29f804bacde50cf34f9b35502a';

    function run(form, options) {
        var cart = options.cart;
        var productsList = options.productsList || [];
        var total = options.total || 0;
        var deliveryFee = options.deliveryFee || (typeof DELIVERY_FEE !== 'undefined' ? DELIVERY_FEE : 4800);
        var grandTotal = options.grandTotal || (total + deliveryFee);

        if (!cart || cart.length === 0) {
            alert('Your cart is empty.');
            return;
        }
        if (grandTotal <= 0) {
            alert('Invalid order total. Please check your cart.');
            return;
        }
        if (typeof PaystackPop === 'undefined') {
            alert('Payment system is loading. Please wait a moment and try again.');
            return;
        }

        var email = (document.getElementById('email') || {}).value.trim();
        var fullname = (document.getElementById('fullname') || {}).value.trim();
        var phone = (document.getElementById('phone') || {}).value.trim();
        var address1 = (document.getElementById('address1') || {}).value.trim();
        var state = (document.getElementById('state') || {}).value;
        var country = (document.getElementById('country') || {}).value;

        if (!email || !fullname || !phone || !address1 || !state) {
            alert('Please fill in all required fields: Name, Email, Phone, Address, and State.');
            return;
        }
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address.');
            return;
        }

        var nameParts = fullname.trim().split(' ');
        var firstName = nameParts[0] || '';
        var lastName = nameParts.slice(1).join(' ') || '';
        var orderSummary = cart.map(function(item) {
            var product = productsList.find(function(p) { return p.id === item.id; });
            if (!product) return 'Item ' + item.id + ' x ' + item.quantity;
            var itemTotal = product.price * item.quantity;
            return product.name + ' x ' + item.quantity + ' = ₦' + (typeof formatPrice === 'function' ? formatPrice(itemTotal) : itemTotal.toLocaleString('en-NG', { minimumFractionDigits: 2 }));
        }).join('; ');
        var addressLine = [address1, state, country].filter(Boolean).join(', ');

        if (typeof trackAddPaymentInfo === 'function') {
            trackAddPaymentInfo('paystack', { value: grandTotal, currency: 'NGN' });
        } else {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({ event: 'add_payment_info', payment_method: 'paystack', ecommerce: { currency: 'NGN', value: grandTotal } });
        }

        try {
            var handler = PaystackPop.setup({
                key: PAYSTACK_PUBLIC_KEY,
                email: email,
                amount: Math.round(grandTotal * 100),
                currency: 'NGN',
                ref: 'PUPPIARY_' + Date.now(),
                metadata: {
                    fullname: fullname,
                    firstname: firstName,
                    lastname: lastName,
                    phone: phone,
                    email: email,
                    address: { address1: address1, state: state, country: country },
                    cart: cart.map(function(i) {
                        var product = productsList.find(function(p) { return p.id === i.id; });
                        return {
                            id: i.id,
                            name: product ? product.name : 'Item ' + i.id,
                            price: product ? product.price : undefined,
                            quantity: i.quantity,
                            line_total: product ? +(product.price * i.quantity).toFixed(2) : undefined
                        };
                    }),
                    subtotal: +total.toFixed(2),
                    delivery_fee: +deliveryFee.toFixed(2),
                    total: +grandTotal.toFixed(2),
                    custom_fields: [
                        { display_name: 'Customer Name', variable_name: 'customer_name', value: fullname },
                        { display_name: 'Phone', variable_name: 'phone', value: phone },
                        { display_name: 'Address', variable_name: 'address', value: addressLine },
                        { display_name: 'Order Items', variable_name: 'order_items', value: orderSummary },
                        { display_name: 'Order Total (NGN)', variable_name: 'order_total', value: '₦' + (typeof formatPrice === 'function' ? formatPrice(grandTotal) : grandTotal.toLocaleString('en-NG', { minimumFractionDigits: 2 })) }
                    ]
                },
                callback: function(response) {
                    var orderData = {
                        transaction_id: response.reference,
                        cart: cart,
                        total: total,
                        delivery_fee: deliveryFee,
                        grand_total: grandTotal,
                        email: email
                    };
                    localStorage.setItem('puppiary-order-data', JSON.stringify(orderData));
                    try { if (typeof clearCart === 'function') clearCart(); if (typeof updateCartCounter === 'function') updateCartCounter(); } catch (err) {}
                    window.location.href = '/success?ref=' + encodeURIComponent(response.reference);
                },
                onClose: function() {}
            });
            handler.openIframe();
        } catch (error) {
            console.error('Paystack setup error:', error);
            alert('An error occurred while setting up payment. Please try again.');
        }
    }

    window.PaystackCheckout = { run: run };
})();
