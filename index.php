<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/products_data.php';

$home_products = array_values(array_filter($products, function ($p) {
    if (empty($p['published'])) return false;
    if (isset($p['list_in_catalog']) && $p['list_in_catalog'] === false) return false;
    return true;
}));

if (!function_exists('format_price_php')) {
    function format_price_php($price) {
        return number_format((float) $price, 2, '.', ',');
    }
}

if (!function_exists('product_display_price')) {
    function product_display_price($p) {
        if (defined('CURRENCY_IS_NGN') && CURRENCY_IS_NGN) {
            return ['symbol' => '₦', 'value' => $p['price'], 'formatted' => format_price_php($p['price'])];
        }
        $usd = isset($p['price_usd']) ? $p['price_usd'] : round($p['price'] / 1500, 2);
        return ['symbol' => '$', 'value' => $usd, 'formatted' => number_format((float) $usd, 2, '.', ',')];
    }
}

$page_title = 'Puppy Toys, Teething & Starter Kits | Non-Toxic Supplies | Puppiary';
$page_description = 'The ultimate resource for new puppy parents. Shop durable chew toys, training gear, and comfort essentials designed to solve teething pain and separation anxiety.';
$page_canonical = '/';
$page_keywords = 'puppy toys, puppy chew toys, puppy teething toys, puppy starter kits, non-toxic puppy toys, puppy supplies, puppy training gear';
$body_class = 'home';
$extra_head = '    <link rel="preload" href="/products/calming-dog-bed/calming-dog-bed-1.webp" as="image" fetchpriority="high">';
$json_ld_scripts = [
    ['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => SITE_NAME, 'url' => SITE_URL],
    ['@context' => 'https://schema.org', '@type' => 'WebSite', 'name' => SITE_NAME, 'url' => SITE_URL, 'publisher' => ['@type' => 'Organization', 'name' => SITE_NAME], 'potentialAction' => ['@type' => 'SearchAction', 'target' => ['@type' => 'EntryPoint', 'urlTemplate' => SITE_URL . '/products?search={search_term_string}'], 'query-input' => 'required name=search_term_string']]
];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="home-trust-bar" aria-label="Why shop with Puppiary">
            <div class="home-trust-bar-inner">
                <div class="trust-item">
                    <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11.8h2c0 1.7 1.3 3 3 3s3-1.3 3-3h6c0 1.7 1.3 3 3 3s3-1.3 3-3h2v-5l-3-4z"/></svg>
                    <span>Fast Lagos Delivery</span>
                </div>
                <div class="trust-item">
                    <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-1 6h2v6h-2V7zm0 8h2v2h-2v-2z"/></svg>
                    <span>Secure Paystack Checkout</span>
                </div>
                <div class="trust-item">
                    <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <span>30-Day Money-Back Guarantee</span>
                </div>
                <div class="trust-item">
                    <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    <span>4.8★ from 2,500+ Happy Customers</span>
                </div>
            </div>
        </section>
        <section class="promo-banner" aria-label="Puppiary">
            <div class="promo-banner-container">
                <div class="promo-banner-image">
                    <img src="/images/puppiary-homepage-promotional-banner.webp" alt="Three dogs laying down on calming comfort bed - Puppiary" loading="eager" decoding="async" fetchpriority="high">
                </div>
                <div class="promo-banner-content">
                    <h2 class="promo-banner-headline">Puppiary Solving Puppies&rsquo; Daily Problems</h2>
                    <p class="promo-banner-description">Simple, effective products designed to keep your puppy happy, healthy, and comfortable.</p>
                    <div class="promo-banner-actions">
                        <a href="/products" class="btn btn-promo-primary">Shop</a>
                        <a href="/about" class="btn btn-promo-secondary">Our Story</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="shop-section home-products-section" aria-label="Our Products">
            <h2>Our Products</h2>
            <?php if (count($home_products) === 0): ?>
                <p class="no-results">No products available right now. Check back soon!</p>
            <?php else: ?>
                <div class="product-grid" id="home-product-list">
                    <?php foreach ($home_products as $p): ?>
                        <a href="/product/<?php echo htmlspecialchars($p['slug']); ?>" class="product-card" data-product-id="<?php echo (int)$p['id']; ?>">
                            <img src="<?php echo htmlspecialchars($p['images'][0]); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="product-card-image" loading="lazy" decoding="async" width="800" height="600">
                            <div class="product-card-content">
                                <h3 class="product-card-name"><?php echo htmlspecialchars($p['name']); ?></h3>
                                <p class="product-card-category"><?php echo htmlspecialchars($p['category']); ?></p>
                                <p class="product-card-description"><?php echo htmlspecialchars($p['shortDescription'] ?? ''); ?></p>
                                <div class="product-card-footer">
                                    <?php $dp = product_display_price($p); ?>
                                    <span class="product-card-price"><?php echo htmlspecialchars($dp['symbol'] . $dp['formatted']); ?></span>
                                    <div class="product-card-actions">
                                        <button type="button" class="btn btn-primary add-to-cart-btn" data-product-id="<?php echo (int)$p['id']; ?>">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
<?php
$footer_scripts = '<script>window.products = ' . json_encode($products, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';</script>';
$footer_scripts .= '<script>document.addEventListener("DOMContentLoaded", function() {
    if (typeof trackViewItemList === "function") {
        var list = ' . json_encode($home_products, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';
        if (list.length) trackViewItemList("Homepage", list);
    }
    var grid = document.getElementById("home-product-list");
    if (grid) {
        grid.querySelectorAll(".product-card[data-product-id]").forEach(function(card) {
            card.addEventListener("click", function(e) {
                if (e.target.closest(".add-to-cart-btn")) return;
                var id = card.getAttribute("data-product-id");
                var product = window.products && window.products.find(function(p) { return String(p.id) === id; });
                if (product && typeof trackSelectItem === "function") trackSelectItem("Homepage", product);
            });
        });
    }
});</script>';
require __DIR__ . '/includes/footer.php';
?>
