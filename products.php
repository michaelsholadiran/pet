<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/products_data.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = ''; // Category filter disabled – kept for HTML only (hidden)

// Filter: published only, list_in_catalog not false (e.g. test products hidden), then by search
$filtered = array_filter($products, function ($p) {
    if (empty($p['published'])) return false;
    if (isset($p['list_in_catalog']) && $p['list_in_catalog'] === false) return false;
    return true;
});
if ($search !== '') {
    $searchLower = mb_strtolower($search);
    $filtered = array_filter($filtered, function ($p) use ($searchLower) {
        $name = mb_strtolower($p['name']);
        $short = isset($p['shortDescription']) ? mb_strtolower($p['shortDescription']) : '';
        return (strpos($name, $searchLower) !== false || strpos($short, $searchLower) !== false);
    });
}
$filtered = array_values($filtered);

$inStock = array_filter($filtered, function ($p) { return isset($p['stock']) && $p['stock'] > 0; });
$outOfStock = array_filter($filtered, function ($p) { return !isset($p['stock']) || $p['stock'] <= 0; });
$showRestocking = count($outOfStock) > count($inStock) && count($outOfStock) >= 3;

function format_price_php($price) {
    return number_format((float) $price, 2, '.', ',');
}

function product_display_price($p) {
    if (defined('CURRENCY_IS_NGN') && CURRENCY_IS_NGN) {
        return ['symbol' => '₦', 'value' => $p['price'], 'formatted' => format_price_php($p['price'])];
    }
    $usd = isset($p['price_usd']) ? $p['price_usd'] : round($p['price'] / 1500, 2);
    return ['symbol' => '$', 'value' => $usd, 'formatted' => number_format((float) $usd, 2, '.', ',')];
}

$page_title = 'Shop - Puppiary';
$page_description = 'Browse our full catalog across categories. Filter by category and search to find your next favorite.';
$page_canonical = '/products';
$current_nav = 'shop';
$json_ld_scripts = [
    ['@context' => 'https://schema.org', '@type' => 'CollectionPage', 'name' => $page_title, 'url' => SITE_URL . '/products', 'description' => $page_description]
];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="shop-section">
            <h1>Our Products</h1>
            <form method="get" action="/products" class="shop-controls" role="search">
                <input type="text" name="search" id="search-bar" class="search-bar" placeholder="Search products..." aria-label="Search products" value="<?php echo htmlspecialchars($search); ?>">
                <span class="shop-controls-category" style="display: none;"><select name="category" id="category-filter" class="category-filter" aria-label="Filter by category">
                    <option value="">Sort by</option>
                    <option value="Training & Safety"<?php echo $category === 'Training & Safety' ? ' selected' : ''; ?>>Training & Safety</option>
                    <option value="Play & Teething"<?php echo $category === 'Play & Teething' ? ' selected' : ''; ?>>Play & Teething</option>
                    <option value="Grooming & Comfort"<?php echo $category === 'Grooming & Comfort' ? ' selected' : ''; ?>>Grooming & Comfort</option>
                    <option value="Feeding"<?php echo $category === 'Feeding' ? ' selected' : ''; ?>>Feeding</option>
                </select></span>
            </form>

            <?php if (count($filtered) === 0): ?>
                <p id="no-results" class="no-results">No products found. Try adjusting your filters.</p>
            <?php elseif ($showRestocking): ?>
                <div class="restocking-section">
                    <div class="restocking-header">
                        <div class="restocking-icon">🔄</div>
                        <h2>Restocking Soon!</h2>
                        <p>We're working hard to bring back our full range of products. Check back soon!</p>
                    </div>
                    <div class="available-products-section">
                        <h3>✨ Currently Available</h3>
                        <p>While you wait, check out these popular items that are ready to ship:</p>
                        <div class="available-products-grid">
                            <?php foreach ($inStock as $p): ?>
                                <div class="product-card" role="link" tabindex="0" data-href="/product/<?php echo htmlspecialchars($p['slug']); ?>" data-product-id="<?php echo (int)$p['id']; ?>">
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
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="restocking-benefits">
                        <div class="benefit-card"><div class="benefit-icon">🚚</div><h4>Fast Shipping</h4><p>Nationwide delivery in 1-4 business days</p></div>
                        <div class="benefit-card"><div class="benefit-icon">💚</div><h4>Quality Guaranteed</h4><p>All products are vet-approved and safe for puppies</p></div>
                        <div class="benefit-card"><div class="benefit-icon">📞</div><h4>Expert Support</h4><p>Get personalized advice from our puppy care specialists</p></div>
                    </div>
                    <div class="newsletter-signup">
                        <h3>🔔 Get Notified When We Restock</h3>
                        <p>Be the first to know when your favorite products are back in stock!</p>
                        <div class="newsletter-form">
                            <input type="email" placeholder="Enter your email address" class="newsletter-input">
                            <button type="button" class="btn btn-primary newsletter-btn">Notify Me</button>
                        </div>
                        <p class="newsletter-note">No spam, unsubscribe anytime.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="product-grid" id="product-list">
                    <?php foreach ($filtered as $p): ?>
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
// ItemList JSON-LD for SEO (PHP-driven)
$item_list_ld = [
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'itemListElement' => [],
];
foreach ($filtered as $i => $p) {
    $item_list_ld['itemListElement'][] = [
        '@type' => 'ListItem',
        'position' => $i + 1,
        'url' => 'https://www.puppiary.com/product/' . ($p['slug'] ?? $p['id']),
    ];
}
$footer_scripts = '<script type="application/ld+json">' . json_encode($item_list_ld, JSON_UNESCAPED_SLASHES) . '</script>';
$footer_scripts .= '<script>window.products = ' . json_encode($products, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';</script>';
$footer_scripts .= '<script>window.productListFiltered = ' . json_encode($filtered, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';</script>';
$footer_scripts .= '<script>document.addEventListener("DOMContentLoaded", function() {
    var form = document.querySelector(".shop-section form[method=\"get\"]");
    var cat = document.getElementById("category-filter");
    if (form && cat) cat.addEventListener("change", function() { form.submit(); });
    var searchBar = document.getElementById("search-bar");
    if (form && searchBar) searchBar.addEventListener("keypress", function(e) { if (e.key === "Enter") form.submit(); });
    if (typeof trackViewItemList === "function" && window.productListFiltered && window.productListFiltered.length) trackViewItemList("Product List", window.productListFiltered);
    var list = document.getElementById("product-list");
    if (list) {
        list.querySelectorAll(".product-card[data-product-id]").forEach(function(card) {
            card.addEventListener("click", function(e) {
                if (e.target.closest(".add-to-cart-btn")) return;
                var id = card.getAttribute("data-product-id");
                var product = window.products && window.products.find(function(p) { return String(p.id) === id; });
                if (product && typeof trackSelectItem === "function") trackSelectItem("Product List", product);
            });
        });
    }
});</script>';
if ($showRestocking):
    $restocking_ids = array_map(function($p) { return $p['id']; }, $inStock);
    $footer_scripts .= '<script>window.restockingProductIds = ' . json_encode($restocking_ids) . ';</script>';
    $footer_scripts .= '<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof trackViewItemList === "function" && window.products && window.restockingProductIds && window.restockingProductIds.length) {
            var list = window.products.filter(function(p) { return window.restockingProductIds.indexOf(p.id) !== -1; });
            if (list.length) trackViewItemList("Currently Available", list);
        }
        var cards = document.querySelectorAll(".restocking-section .product-card[data-href]");
        cards.forEach(function(card) {
            card.addEventListener("click", function(e) {
                if (e.target.closest(".add-to-cart-btn")) return;
                var id = card.getAttribute("data-product-id");
                var product = window.products && window.products.find(function(p) { return String(p.id) === id; });
                if (product && typeof trackSelectItem === "function") trackSelectItem("Currently Available", product);
                window.location.href = card.getAttribute("data-href");
            });
            card.addEventListener("keydown", function(e) {
                if (e.key === "Enter" || e.key === " ") {
                    e.preventDefault();
                    var id = card.getAttribute("data-product-id");
                    var product = window.products && window.products.find(function(p) { return String(p.id) === id; });
                    if (product && typeof trackSelectItem === "function") trackSelectItem("Currently Available", product);
                    window.location.href = card.getAttribute("data-href");
                }
            });
        });
        var newsletterBtn = document.querySelector(".newsletter-btn");
        var newsletterInput = document.querySelector(".newsletter-input");
        if (newsletterBtn && newsletterInput) {
            newsletterBtn.addEventListener("click", function() {
                var email = newsletterInput.value.trim();
                if (email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    alert("Thank you! We\'ll notify you when products are back in stock.");
                    newsletterInput.value = "";
                } else {
                    alert("Please enter a valid email address.");
                    newsletterInput.focus();
                }
            });
            newsletterInput.addEventListener("keypress", function(e) { if (e.key === "Enter") newsletterBtn.click(); });
        }
    });
    </script>';
endif;
require __DIR__ . '/includes/footer.php';
?>
