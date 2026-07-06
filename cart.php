<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Cart - Puppiary';
$page_description = 'Your shopping cart at Puppiary.';
$page_canonical = '/cart';
$robots_noindex = true;
$body_class = 'cart-redirect';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="cart-section">
            <p>Opening your cart…</p>
        </section>
    </main>
<?php
$footer_scripts = '<script>document.body.dataset.openCart = "true";</script>';
require __DIR__ . '/includes/footer.php';
