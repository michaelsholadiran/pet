<?php
/**
 * Navbar + GTM noscript + mobile drawer.
 * Optional: $current_nav = 'shop'|'about'|'faq'|'contact' for active link
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
                <img src="/logo.webp" alt="" class="logo-image" width="50" height="50">
                <?php echo htmlspecialchars(SITE_NAME); ?>
            </a>
            <nav class="navbar-menu" aria-label="Primary">
                <a href="/products" class="nav-link<?php echo ($current_nav ?? '') === 'shop' ? ' active' : ''; ?>"<?php echo ($current_nav ?? '') === 'shop' ? ' aria-current="page"' : ''; ?>>Shop</a>
                <a href="/about" class="nav-link<?php echo ($current_nav ?? '') === 'about' ? ' active' : ''; ?>"<?php echo ($current_nav ?? '') === 'about' ? ' aria-current="page"' : ''; ?>>About</a>
                <a href="/faq" class="nav-link<?php echo ($current_nav ?? '') === 'faq' ? ' active' : ''; ?>"<?php echo ($current_nav ?? '') === 'faq' ? ' aria-current="page"' : ''; ?>>FAQ</a>
                <a href="/contact" class="nav-link<?php echo ($current_nav ?? '') === 'contact' ? ' active' : ''; ?>"<?php echo ($current_nav ?? '') === 'contact' ? ' aria-current="page"' : ''; ?>>Contact</a>
            </nav>
            <button class="mobile-menu-button" aria-label="Open menu" aria-controls="mobile-drawer" aria-expanded="false">
                <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
                    <path fill="currentColor" d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/>
                </svg>
            </button>
            <a href="/cart" class="cart-icon" aria-label="Cart">
                <span class="cart-icon-text">🛒</span>
                <span class="cart-counter" aria-label="Items in cart">0</span>
            </a>
        </div>
    </header>

    <div class="drawer-overlay" data-overlay hidden></div>
    <aside id="mobile-drawer" class="mobile-drawer" aria-hidden="true" aria-label="Mobile navigation">
        <button class="drawer-close" aria-label="Close menu">
            <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">
                <path fill="currentColor" d="M18.3 5.71 12 12l6.3 6.29-1.41 1.41L10.59 13.4 4.29 19.7 2.88 18.3 9.17 12 2.88 5.71 4.29 4.3l6.3 6.3 6.29-6.3z"/>
            </svg>
        </button>
        <nav class="mobile-drawer-nav" aria-label="Mobile Primary">
            <a href="/products" class="drawer-link">Shop</a>
            <a href="/about" class="drawer-link">About</a>
            <a href="/faq" class="drawer-link">FAQ</a>
            <a href="/contact" class="drawer-link">Contact</a>
        </nav>
    </aside>
