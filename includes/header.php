<?php
/**
 * Navbar + GTM noscript + mobile drawer.
 * Optional: $current_nav = 'shop'|'about'|'faq' for active link
 */
require_once __DIR__ . '/config.php';
?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo htmlspecialchars(GTM_ID); ?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <header class="navbar">
        <div class="navbar-container">
            <a href="/" class="navbar-logo">
                <img src="/logo.webp" alt="<?php echo htmlspecialchars(SITE_NAME); ?> logo" class="logo-image" width="50" height="50">
                <?php echo htmlspecialchars(SITE_NAME); ?>
            </a>
            <nav class="navbar-menu" aria-label="Primary">
                <a href="/products" class="nav-link<?php echo ($current_nav ?? '') === 'shop' ? ' active' : ''; ?>"<?php echo ($current_nav ?? '') === 'shop' ? ' aria-current="page"' : ''; ?>>Shop</a>
                <a href="/about" class="nav-link<?php echo ($current_nav ?? '') === 'about' ? ' active' : ''; ?>"<?php echo ($current_nav ?? '') === 'about' ? ' aria-current="page"' : ''; ?>>About</a>
                <a href="/faq" class="nav-link<?php echo ($current_nav ?? '') === 'faq' ? ' active' : ''; ?>"<?php echo ($current_nav ?? '') === 'faq' ? ' aria-current="page"' : ''; ?>>FAQ</a>
            </nav>
            <button class="mobile-menu-button" aria-label="Open menu" aria-controls="mobile-drawer" aria-expanded="false">
                <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
                    <path fill="currentColor" d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/>
                </svg>
            </button>
            <button type="button" id="cart-toggle" class="cart-icon" aria-label="Open cart" aria-controls="cart-drawer" aria-expanded="false">
                <span class="cart-icon-text">🛒</span>
                <span class="cart-counter" aria-label="Items in cart">0</span>
            </button>
        </div>
    </header>

    <div class="drawer-overlay" data-overlay hidden></div>
    <aside id="mobile-drawer" class="mobile-drawer" aria-hidden="true" inert aria-label="Mobile navigation">
        <button class="drawer-close" aria-label="Close menu">
            <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
                <path fill="currentColor" d="M18.3 5.71 12 12l6.3 6.29-1.41 1.41L10.59 13.4 4.29 19.7 2.88 18.3 9.17 12 2.88 5.71 4.29 4.3l6.3 6.3 6.29-6.3z"/>
            </svg>
        </button>
        <nav class="mobile-drawer-nav" aria-label="Mobile Primary">
            <a href="/products" class="drawer-link">Shop</a>
            <a href="/about" class="drawer-link">About</a>
            <a href="/faq" class="drawer-link">FAQ</a>
        </nav>
    </aside>

    <?php
    $site_trust_items = [
        [
            'icon' => 'M20 8h-3V4H3c-1.1 0-2 .9-2 2v11.8h2c0 1.7 1.3 3 3 3s3-1.3 3-3h6c0 1.7 1.3 3 3 3s3-1.3 3-3h2v-5l-3-4z',
            'label' => 'Delivery in 3–7 days',
        ],
        [
            'icon' => 'M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-1 6h2v6h-2V7zm0 8h2v2h-2v-2z',
            'label' => 'Secure Paystack Checkout',
        ],
        [
            'icon' => 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z',
            'label' => '30-Day Money-Back Guarantee',
        ],
    ];
    ?>
    <section class="home-trust-bar" aria-label="Why shop with Puppiary">
        <div class="home-trust-bar-inner">
            <div class="home-trust-bar-track">
                <?php for ($trust_copy = 0; $trust_copy < 2; $trust_copy++): ?>
                <div class="home-trust-bar-group"<?php echo $trust_copy === 1 ? ' aria-hidden="true"' : ''; ?>>
                    <?php foreach ($site_trust_items as $item): ?>
                    <div class="trust-item">
                        <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="<?php echo htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8'); ?>"/></svg>
                        <span><?php echo htmlspecialchars($item['label']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>
