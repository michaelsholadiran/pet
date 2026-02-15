<?php
require_once __DIR__ . '/includes/products_data.php';
$page_title = 'Checkout - Puppiary';
$page_description = 'Secure checkout at Puppiary.';
$page_canonical = '/checkout';
$robots_noindex = true;
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="checkout-section">
            <h1>Checkout</h1>
            <div class="checkout-container">
                <form id="checkout-form" class="checkout-form">
                    <fieldset>
                        <legend>Shipping Information</legend>
                        <div class="form-group">
                            <label for="fullname">Full Name *</label>
                            <input type="text" id="fullname" name="fullname" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="address1">Address *</label>
                            <input type="text" id="address1" name="address1" required>
                        </div>
                        <div class="form-group">
                            <label for="state">State *</label>
                            <select id="state" name="state" required>
                                <option value="">Select State</option>
                                <option value="Lagos">Lagos</option>
                            </select>
                        </div>
                        <input type="hidden" id="country" name="country" value="Nigeria">
                    </fieldset>
                    <div class="accepted-payment-methods">
                        <p class="payment-methods-label">We Accept:</p>
                        <div class="payment-methods-grid">
                            <div class="payment-method-item" title="Bank Transfer">
                                <svg viewBox="0 0 24 24" width="32" height="32" aria-hidden="true"><path fill="#0066CC" d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                <span>Bank Transfer</span>
                            </div>
                            <div class="payment-method-item" title="Verve"><div class="payment-icon verve">VERVE</div><span>Verve</span></div>
                            <div class="payment-method-item" title="USSD">
                                <svg viewBox="0 0 24 24" width="32" height="32" aria-hidden="true"><path fill="#00A859" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                <span>USSD</span>
                            </div>
                            <div class="payment-method-item" title="OPay"><img src="/images/opay.webp" alt="OPay" class="payment-logo opay-logo"><span>OPay</span></div>
                            <div class="payment-method-item" title="Visa"><div class="payment-icon visa">VISA</div><span>Visa</span></div>
                            <div class="payment-method-item" title="Mastercard">
                                <div class="payment-icon mastercard"><div class="mc-circle mc-red"></div><div class="mc-circle mc-orange"></div></div>
                                <span>Mastercard</span>
                            </div>
                        </div>
                    </div>
                    <div class="checkout-trust-badges">
                        <div class="trust-item"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><path fill="currentColor" d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-1 6h2v6h-2V7zm0 8h2v2h-2v-2z"/></svg><span>Secure Payment</span></div>
                        <div class="trust-item"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg><span>SSL Encrypted</span></div>
                        <div class="trust-item"><svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><path fill="currentColor" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg><span>Money-Back Guarantee</span></div>
                    </div>
                    <div class="checkout-actions">
                        <button type="submit" class="btn btn-primary btn-large">Complete Order</button>
                        <a href="/products" class="btn btn-secondary btn-large">Continue Shopping</a>
                    </div>
                    <div class="checkout-guarantees">
                        <p class="guarantee-text"><strong>✓ 30-Day Money-Back Guarantee</strong><br>Not satisfied? Get a full refund, no questions asked.</p>
                        <p class="guarantee-text"><strong>✓ Free Returns</strong><br>Easy returns within 30 days of delivery.</p>
                        <p class="guarantee-text"><strong>✓ Secure Checkout</strong><br>Your payment information is encrypted and secure.</p>
                    </div>
                </form>
                <aside class="checkout-summary" aria-label="Order Summary">
                    <h3>Order Summary</h3>
                    <div id="order-items"></div>
                    <div class="summary-row" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-top: 1px solid #eee;">
                        <span>Delivery Fee:</span>
                        <span id="delivery-fee">₦0.00</span>
                    </div>
                    <div class="summary-total"><strong>Total: ₦<span id="total-price">0.00</span></strong></div>
                    <div class="checkout-social-proof">
                        <div class="social-proof-item"><div class="proof-stars">★★★★★</div><p class="proof-text"><strong>4.8/5</strong> from 2,500+ happy customers</p></div>
                        <div class="social-proof-item"><svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg><span>Trusted by 10,000+ customers</span></div>
                        <div class="social-proof-item"><svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg><span>Fast &amp; Reliable Delivery</span></div>
                    </div>
                    <div class="payment-security">
                        <p class="security-label">Secured by:</p>
                        <div class="security-badges">
                            <div class="security-badge"><svg viewBox="0 0 24 24" width="32" height="32" aria-hidden="true"><path fill="currentColor" d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-1 6h2v6h-2V7zm0 8h2v2h-2v-2z"/></svg><span>Paystack</span></div>
                            <div class="security-badge"><svg viewBox="0 0 24 24" width="32" height="32" aria-hidden="true"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg><span>SSL Secure</span></div>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </main>
<?php
$footer_scripts = '<script>window.products = ' . json_encode($products, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';</script><script>var products = window.products || [];</script><script src="https://js.paystack.co/v1/inline.js"></script><script src="/js/checkout.js"></script>';
require __DIR__ . '/includes/footer.php';
?>
