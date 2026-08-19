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
?>
    <main>
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
                        <?php /* <button type="button" class="btn btn-promo-secondary starter-kit-btn">Puppy Starter Kit</button> */ ?>
                    </div>
                </div>
            </div>
        </section>
        <?php /* Starter kit section — re-enable when more products are published
        <section class="home-starter-kit-section" aria-label="Puppy Starter Kit">
            <div class="home-starter-kit-inner">
                <h2>Everything Your Puppy Needs. One Simple Starter Kit.</h2>
                <h3 class="home-starter-kit-subtitle">Skip the stress of figuring out what to buy.</h3>
                <p>Bringing home a new puppy is exciting - but knowing what you actually need can feel overwhelming. We&rsquo;ve done the hard work for you.</p>
                <p>Our Puppy Starter Kit includes the essential food, treats, toys, grooming, and training supplies your puppy needs in one carefully selected bundle. No endless searching. No second-guessing. Just everything you need to give your puppy the best start from day one.</p>
                <button type="button" class="btn btn-promo-primary starter-kit-btn">Get Puppy Starter Kit</button>
            </div>
        </section>
        */ ?>
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
