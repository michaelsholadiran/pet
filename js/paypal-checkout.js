(function() {
    var PAYPAL_API = (typeof window.PAYPAL_API !== 'undefined' && window.PAYPAL_API) ? window.PAYPAL_API : '/api/paypal.php';
    var NGN_TO_USD_RATE = 1500;

    function getGrandTotalAndFee(cart, productsList) {
        var total = 0;
        if (cart && cart.length) {
            cart.forEach(function(item) {
                var product = (productsList || []).find(function(p) { return p.id === item.id; });
                if (product) {
                    var unitPrice = (typeof getProductPrice === 'function' ? getProductPrice(product) : product.price);
                    total += unitPrice * item.quantity;
                }
            });
        }
        var deliveryFee = typeof DELIVERY_FEE !== 'undefined' ? DELIVERY_FEE : (typeof window.DELIVERY_FEE !== 'undefined' ? window.DELIVERY_FEE : 4800);
        return { total: total, deliveryFee: deliveryFee, grandTotal: total + deliveryFee };
    }

    var paypalInitialized = false;

    function parseJsonResponse(res) {
        return res.text().then(function(text) {
            if (text.trim().indexOf('<') === 0) {
                console.error('PayPal API returned HTML:', text.slice(0, 300));
                throw new Error('Payment server error. Please try again or use Paystack.');
            }
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error('Invalid response from payment server.');
            }
        });
    }

    function getFormCustomer() {
        var email = (document.getElementById('email') || {}).value.trim();
        var fullname = (document.getElementById('fullname') || {}).value.trim();
        var phone = (document.getElementById('phone') || {}).value.trim();
        var address1 = (document.getElementById('address1') || {}).value.trim();
        var state = (document.getElementById('state') || {}).value;
        var postalCode = (document.getElementById('postal_code') || {}).value.trim();
        var country = (document.getElementById('country') || {}).value;
        return { email: email, fullname: fullname, phone: phone, address1: address1, state: state, postal_code: postalCode, country: country };
    }

    function validateForm() {
        var c = getFormCustomer();
        if (!c.email || !c.fullname || !c.phone || !c.address1 || !c.state || !c.postal_code) {
            alert('Please fill in all required fields: Name, Email, Phone, Address, State, and Postal / ZIP code.');
            return false;
        }
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(c.email)) {
            alert('Please enter a valid email address.');
            return false;
        }
        return true;
    }

    function init(options) {
        var paypalContainer = options.paypalContainer;
        var checkoutForm = options.checkoutForm;
        var checkoutSubmitBtn = options.checkoutSubmitBtn;
        var cart = options.cart || [];
        var productsList = options.productsList || [];
        var total = options.total || 0;
        var deliveryFee = options.deliveryFee || (typeof DELIVERY_FEE !== 'undefined' ? DELIVERY_FEE : 4800);
        var grandTotal = options.grandTotal || (total + deliveryFee);

        if (paypalInitialized) return;
        if (typeof window.paypal === 'undefined') {
            window.onPayPalWebSdkLoaded = function() { init(options); };
            return;
        }
        var button = document.querySelector('paypal-button');
        if (!button) return;

        var apiBase = (typeof window.PAYPAL_API !== 'undefined' && window.PAYPAL_API) ? window.PAYPAL_API : PAYPAL_API;
        fetch(apiBase + '?action=client-token')
            .then(function(res) {
                return res.text().then(function(text) {
                    if (text.trim().indexOf('<') === 0) throw new Error('Payment server error.');
                    try {
                        var data = JSON.parse(text);
                        if (data.code === 'PAYPAL_NOT_CONFIGURED' || (data.error && data.error.indexOf('PayPal') !== -1 && (data.error.indexOf('not configured') !== -1 || data.error.indexOf('401') !== -1))) {
                            var err = new Error(data.error || 'PayPal not configured');
                            err.code = 'PAYPAL_NOT_CONFIGURED';
                            err.reason = (data.error && data.error.indexOf('401') !== -1) ? 'invalid_credentials' : null;
                            throw err;
                        }
                        return data;
                    } catch (e) {
                        if (e.code === 'PAYPAL_NOT_CONFIGURED') throw e;
                        if (e.message && e.message.indexOf('PayPal is not configured') !== -1) throw e;
                        throw new Error(e.message || 'Invalid response from payment server.');
                    }
                });
            })
            .then(function(data) {
                if (data.error) throw new Error(data.error);
                var token = data.clientToken || data.access_token;
                if (!token) throw new Error('No token from PayPal.');
                return window.paypal.createInstance({
                    clientToken: token,
                    components: ['paypal-payments'],
                    pageType: 'checkout'
                });
            })
            .then(function(sdk) {
                return sdk.findEligibleMethods({ currencyCode: 'USD' }).then(function(methods) {
                    if (!methods.isEligible('paypal')) return null;
                    return sdk;
                });
            })
            .then(function(sdk) {
                if (!sdk) return;
                paypalInitialized = true;
                var placeholder = document.getElementById('paypal-placeholder');
                if (placeholder) placeholder.style.display = 'none';
                button.removeAttribute('hidden');

                var session = sdk.createPayPalOneTimePaymentSession({
                    onApprove: function(data) {
                        var api = (typeof window.PAYPAL_API !== 'undefined' && window.PAYPAL_API) ? window.PAYPAL_API : PAYPAL_API;
                        return fetch(api + '?action=capture&order_id=' + encodeURIComponent(data.orderId), { method: 'POST' })
                            .then(function(r) { return parseJsonResponse(r); })
                            .then(function(result) {
                                if (result.error) throw new Error(result.error);
                                var c = getFormCustomer();
                                var orderData = {
                                    transaction_id: result.id || data.orderId,
                                    cart: cart,
                                    total: total,
                                    delivery_fee: deliveryFee,
                                    grand_total: grandTotal,
                                    email: c.email,
                                    fullname: c.fullname,
                                    phone: c.phone,
                                    address1: c.address1,
                                    state: c.state,
                                    country: c.country
                                };
                                localStorage.setItem('puppiary-order-data', JSON.stringify(orderData));
                                try { if (typeof clearCart === 'function') clearCart(); if (typeof updateCartCounter === 'function') updateCartCounter(); } catch (err) {}
                                window.location.href = '/success?ref=' + encodeURIComponent(data.orderId);
                            });
                    },
                    onCancel: function() {},
                    onError: function(err) { console.error('PayPal error', err); alert('PayPal error. Please try again.'); }
                });

                button.onclick = function() {
                    if (!validateForm()) return;
                    var customer = getFormCustomer();
                    var amounts = getGrandTotalAndFee(cart, productsList);
                    var usdValue = (typeof window.CURRENCY !== 'undefined' && window.CURRENCY === 'USD')
                        ? Math.max(0.01, Number(amounts.grandTotal).toFixed(2))
                        : Math.max(0.01, (amounts.grandTotal / NGN_TO_USD_RATE).toFixed(2));
                    var usdValueNum = parseFloat(usdValue, 10);
                    if (typeof trackAddPaymentInfo === 'function') {
                        trackAddPaymentInfo('paypal', cart, amounts.total, amounts.deliveryFee, 'USD', usdValueNum);
                    } else if (typeof pushDataLayer === 'function' && typeof buildEcommerceItem === 'function') {
                        var productsListRef = productsList || (typeof window.products !== 'undefined' ? window.products : []) || [];
                        var paypalItems = cart.map(function(item) {
                            var product = productsListRef.find(function(p) { return p.id === item.id; });
                            return product ? buildEcommerceItem(product, item.quantity) : null;
                        }).filter(Boolean);
                        pushDataLayer({ event: 'add_payment_info', payment_method: 'paypal', ecommerce: { currency: 'USD', value: usdValueNum, items: paypalItems } });
                    } else {
                        window.dataLayer = window.dataLayer || [];
                        window.dataLayer.push({ ecommerce: null });
                        window.dataLayer.push({ event: 'add_payment_info', payment_method: 'paypal', ecommerce: { currency: 'USD', value: usdValueNum } });
                    }
                    function createOrder() {
                        var api = (typeof window.PAYPAL_API !== 'undefined' && window.PAYPAL_API) ? window.PAYPAL_API : PAYPAL_API;
                        return fetch(api + '?action=create-order', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                amount: { value: String(usdValue), currency_code: 'USD' },
                                customer: {
                                    fullname: customer.fullname,
                                    email: customer.email,
                                    phone: customer.phone,
                                    address1: customer.address1,
                                    state: customer.state,
                                    country: customer.country
                                }
                            })
                        })
                            .then(function(r) { return parseJsonResponse(r); })
                            .then(function(data) {
                                if (data.error) throw new Error(data.error);
                                return { orderId: data.id };
                            });
                    }
                    session.start({ presentationMode: 'auto' }, createOrder());
                };
            })
            .catch(function(err) {
                console.warn('PayPal init failed', err);
                paypalInitialized = false;
                var isNotConfigured = (err && (err.code === 'PAYPAL_NOT_CONFIGURED' || (err.message && err.message.indexOf('PayPal is not configured') !== -1)));
                if (isNotConfigured) {
                    if (paypalContainer) paypalContainer.removeAttribute('hidden');
                    var placeholder = document.getElementById('paypal-placeholder');
                    if (placeholder) {
                        var msg = (err && err.message) ? err.message : 'PayPal could not load.';
                        if (msg.indexOf('401') !== -1 || (err && err.reason === 'invalid_credentials')) {
                            placeholder.innerHTML = 'PayPal rejected the credentials. In <code>api/paypal.php</code> use the <strong>Client ID</strong> and <strong>Secret</strong> from your Sandbox app at <a href="https://developer.paypal.com/dashboard/applications/sandbox" target="_blank" rel="noopener">developer.paypal.com</a>. If you regenerated the Secret, paste the new one.';
                        } else {
                            placeholder.innerHTML = msg + ' Add your Sandbox credentials in <code>api/paypal.php</code> or choose Paystack above to pay.';
                        }
                        placeholder.style.display = '';
                    }
                    var paystackRadio = checkoutForm && checkoutForm.querySelector('input[name="payment_method"][value="paystack"]');
                    if (paystackRadio) { paystackRadio.checked = true; paystackRadio.dispatchEvent(new Event('change', { bubbles: true })); }
                    if (checkoutSubmitBtn) checkoutSubmitBtn.style.display = '';
                } else {
                    alert('PayPal could not load: ' + (err && err.message ? err.message : 'Please try again or use Paystack.'));
                }
            });
    }

    window.PaypalCheckout = { init: init };
})();
