<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/products_data.php';
require_once __DIR__ . '/includes/product_display.php';
require_once __DIR__ . '/includes/seo_helpers.php';

$home_products = array_values(array_filter($products, function ($p) {
    if (empty($p['published'])) return false;
    if (isset($p['list_in_catalog']) && $p['list_in_catalog'] === false) return false;
    return true;
}));

$page_title = 'Puppy Toys, Teething & Starter Kits | Non-Toxic Supplies | Puppiary';
$page_description = 'The ultimate resource for new puppy parents. Shop durable chew toys, training gear, and comfort essentials designed to solve teething pain and separation anxiety.';
$page_canonical = '/';
$page_keywords = 'puppy toys, puppy chew toys, puppy teething toys, puppy starter kits, puppy starter kit Nigeria, non-toxic puppy toys, puppy supplies, puppy training gear, puppy food Lagos, puppy harness, puppy collar, calming dog bed, puppy pads, puppy shampoo, puppy treats, freeze-dried training treats, stainless steel puppy bowl, enzymatic cleaner, Lagos puppy delivery, buy puppy products Nigeria, Puppiary';
$body_class = 'home';
$extra_head = '    <link rel="preload" href="/products/calming-dog-bed/calming-dog-bed-1.webp" as="image" fetchpriority="high">';
$json_ld_scripts = [
    puppiary_organization_ld(),
    ['@context' => 'https://schema.org', '@type' => 'WebSite', 'name' => SITE_NAME, 'url' => SITE_URL, 'publisher' => puppiary_organization_ld(), 'potentialAction' => ['@type' => 'SearchAction', 'target' => ['@type' => 'EntryPoint', 'urlTemplate' => SITE_URL . '/products?search={search_term_string}'], 'query-input' => 'required name=search_term_string']],
    puppiary_item_list_ld($home_products),
];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';

$home_trust_items = [
    [
        'icon' => 'M20 8h-3V4H3c-1.1 0-2 .9-2 2v11.8h2c0 1.7 1.3 3 3 3s3-1.3 3-3h6c0 1.7 1.3 3 3 3s3-1.3 3-3h2v-5l-3-4z',
        'label' => 'Fast delivery',
    ],
    [
        'icon' => 'M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-1 6h2v6h-2V7zm0 8h2v2h-2v-2z',
        'label' => 'Secure Paystack Checkout',
    ],
    [
        'icon' => 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z',
        'label' => '30-Day Money-Back Guarantee',
    ],
    [
        'icon' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z',
        'label' => '2,500+ Happy Customers',
    ],
];
?>
    <main>
        <section class="home-trust-bar" aria-label="Why shop with Puppiary">
            <div class="home-trust-bar-inner">
                <div class="home-trust-bar-track">
                    <?php for ($trust_copy = 0; $trust_copy < 2; $trust_copy++): ?>
                    <div class="home-trust-bar-group"<?php echo $trust_copy === 1 ? ' aria-hidden="true"' : ''; ?>>
                        <?php foreach ($home_trust_items as $item): ?>
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
                        <button type="button" class="btn btn-promo-secondary starter-kit-btn">Puppy Starter Kit</button>
                    </div>
                </div>
            </div>
        </section>
        <section class="home-starter-kit-section" aria-label="Puppy Starter Kit">
            <div class="home-starter-kit-inner">
                <h2>Everything Your Puppy Needs. One Simple Starter Kit.</h2>
                <h3 class="home-starter-kit-subtitle">Skip the stress of figuring out what to buy.</h3>
                <p>Bringing home a new puppy is exciting - but knowing what you actually need can feel overwhelming. We&rsquo;ve done the hard work for you.</p>
                <p>Our Puppy Starter Kit includes the essential food, treats, toys, grooming, and training supplies your puppy needs in one carefully selected bundle. No endless searching. No second-guessing. Just everything you need to give your puppy the best start from day one.</p>
                <button type="button" class="btn btn-promo-primary starter-kit-btn">Get Puppy Starter Kit</button>
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
                                    <?php require __DIR__ . '/includes/product_card_actions.php'; ?>
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
                if (e.target.closest(".add-to-cart-btn") || e.target.closest(".product-card-qty")) return;
                var id = card.getAttribute("data-product-id");
                var product = window.products && window.products.find(function(p) { return String(p.id) === id; });
                if (product && typeof trackSelectItem === "function") trackSelectItem("Homepage", product);
            });
        });
    }
});</script>';
require __DIR__ . '/includes/footer.php';
?>
