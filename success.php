<?php
require_once __DIR__ . '/includes/products_data.php';
$page_title = 'Order Confirmed - Puppiary';
$page_description = 'Order confirmation for your Puppiary purchase.';
$page_canonical = '/success';
$robots_noindex = true;
$extra_head = '<style>
.success-hero { background: none; text-align: center; min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 4rem 2rem; }
.success-inner { width: 100%; max-width: 720px; margin: 0 auto; padding: 0 1rem; }
.success-actions { margin-top: 2rem; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
</style>';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="success-hero">
            <div class="success-inner" role="status" aria-live="polite">
                <h1>Order Confirmed</h1>
                <p>Thank you for your purchase. Your payment has been received.</p>
                <p>Reference: <strong id="order-ref">—</strong></p>
                <div class="success-actions">
                    <a href="/" class="btn btn-primary">Back to Home</a>
                    <a href="/products" class="btn btn-secondary">Continue Shopping</a>
                </div>
            </div>
        </section>
    </main>
<?php
$footer_scripts = '<script>window.products = ' . json_encode($products, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';</script><script>var products = window.products || [];</script><script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script><script>
document.addEventListener("DOMContentLoaded", function() {
    var params = new URLSearchParams(window.location.search);
    var ref = params.get("ref");
    if (ref) {
        var el = document.getElementById("order-ref");
        if (el) el.textContent = ref;
    }
    var orderDataStr = localStorage.getItem("puppiary-order-data");
    if (orderDataStr && typeof trackPurchase === "function") {
        try {
            var orderData = JSON.parse(orderDataStr);
            trackPurchase(orderData.transaction_id || ref, orderData.cart || [], orderData.total || 0, orderData.delivery_fee || 0, orderData.email || "");
            localStorage.removeItem("puppiary-order-data");
        } catch (e) { console.error("Error tracking purchase:", e); }
    }
});
if (typeof confetti === "function") {
    var duration = 1500, end = Date.now() + duration;
    (function frame() {
        confetti({ particleCount: 60, spread: 70, origin: { y: 0.6 } });
        if (Date.now() < end) requestAnimationFrame(frame);
    })();
    confetti({ particleCount: 80, angle: 60, spread: 55, origin: { x: 0 } });
    confetti({ particleCount: 80, angle: 120, spread: 55, origin: { x: 1 } });
}
</script>';
require __DIR__ . '/includes/footer.php';
?>
