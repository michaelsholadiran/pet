<?php
require_once __DIR__ . '/includes/products_data.php';
$page_title = 'Cart - Puppiary';
$page_description = 'Your shopping cart at Puppiary.';
$page_canonical = '/cart';
$robots_noindex = true;
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="cart-section">
            <h1>Shopping Cart</h1>
            <div id="cart-content"></div>
        </section>
    </main>
<?php
$footer_scripts = '<script>window.products = ' . json_encode($products, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';</script><script>var products = window.products || [];</script><script src="/js/cart.js"></script>';
require __DIR__ . '/includes/footer.php';
?>
