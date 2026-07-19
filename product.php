<?php
require_once __DIR__ . '/includes/products_data.php';
require_once __DIR__ . '/includes/product_display.php';
require_once __DIR__ . '/includes/seo_helpers.php';
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
if ($slug === '' && !empty($_SERVER['REQUEST_URI'])) {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = array_values(array_filter(explode('/', trim($path, '/'))));
    if (count($parts) >= 2 && $parts[0] === 'product') {
        $slug = $parts[1];
    }
}
$idParam = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$product_ld = null;
$matched = null;
if ($slug !== '') {
    foreach ($products as $p) {
        if (isset($p['slug']) && $p['slug'] === $slug) {
            $matched = $p;
            break;
        }
    }
} elseif ($idParam > 0) {
    foreach ($products as $p) {
        if (isset($p['id']) && (int) $p['id'] === $idParam) {
            $matched = $p;
            break;
        }
    }
}

// Unknown slug/id or unpublished → full 404 page (not the product shell + JS message)
if ($slug !== '' || $idParam > 0) {
    if ($matched === null || empty($matched['published'])) {
        if (!headers_sent()) {
            header('HTTP/1.1 404 Not Found');
            header('Status: 404 Not Found');
        }
        require __DIR__ . '/404.php';
        exit;
    }
    $product_ld = $matched;
}
$page_title = $product_ld ? ($product_ld['name'] . ' - Puppiary') : 'Product - Puppiary';
$page_description = $product_ld ? ($product_ld['shortDescription'] ?? 'Shop quality puppy products at Puppiary.') : 'Shop quality puppy products at Puppiary.';
$page_canonical = '/product' . ($slug ? '/' . $slug : '');
$page_keywords = $product_ld
    ? ($product_ld['keywords'] ?? ($product_ld['name'] . ', ' . ($product_ld['category'] ?? 'puppy supplies') . ', buy puppy products Nigeria, Puppiary'))
    : '';
$page_og_type = $product_ld ? 'product' : 'website';
$page_og_image = $product_ld ? $product_ld['images'][0] : null;
$page_og_image_alt = $product_ld ? $product_ld['name'] : null;
$robots_noindex = !$product_ld;
$json_ld_scripts = [];
if ($product_ld) {
    $json_ld_scripts[] = puppiary_product_ld($product_ld);
    $json_ld_scripts[] = puppiary_breadcrumb_ld([
        ['name' => 'Home', 'url' => '/'],
        ['name' => 'Shop', 'url' => '/products'],
        ['name' => $product_ld['name'], 'url' => '/product/' . $product_ld['slug']],
    ]);
}
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
$delivery_fee_display = product_display_price(['price' => DELIVERY_FEE_NGN]);
?>
    <main>
        <section class="product-detail" id="product-detail"<?php echo $product_ld ? ' data-product-slug="' . htmlspecialchars($product_ld['slug']) . '"' : ''; ?>>
            <?php if ($product_ld): ?>
                <?php
                $p = $product_ld;
                $dp = product_display_price($p);
                $in_stock = isset($p['stock']) && $p['stock'] > 0;
                ?>
                <nav class="product-breadcrumb" aria-label="Breadcrumb">
                    <a href="/">Home</a> / <a href="/products">Shop</a> / <span aria-current="page"><?php echo htmlspecialchars($p['name']); ?></span>
                </nav>
                <div class="product-detail-container">
                    <div class="product-image-section">
                        <img src="<?php echo htmlspecialchars($p['images'][0]); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="product-detail-image" id="main-product-image" loading="eager" decoding="async" fetchpriority="high" width="800" height="600">
                        <?php if (count($p['images']) > 1): ?>
                        <div class="product-thumbnails">
                            <?php foreach ($p['images'] as $index => $img): ?>
                                <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="product-thumbnail<?php echo $index === 0 ? ' active' : ''; ?>" data-image-index="<?php echo (int) $index; ?>" loading="lazy" decoding="async">
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="product-info-section">
                        <h1><?php echo htmlspecialchars($p['name']); ?></h1>
                        <p class="product-category">Category: <?php echo htmlspecialchars($p['category']); ?></p>
                        <p class="product-price"><?php echo htmlspecialchars($dp['symbol'] . $dp['formatted']); ?></p>
                        <p class="product-stock <?php echo $in_stock ? 'in-stock' : 'out-of-stock'; ?>"><?php echo $in_stock ? ((int) $p['stock'] . ' in stock') : 'Out of stock'; ?></p>
                        <p class="product-description"><?php echo htmlspecialchars($p['description']); ?></p>
                        <div class="product-actions">
                            <div class="quantity-selector">
                                <button type="button" class="qty-btn" id="qty-decrease" aria-label="Decrease quantity">−</button>
                                <input type="number" id="quantity" value="1" min="1" max="<?php echo (int) $p['stock']; ?>" aria-label="Quantity">
                                <button type="button" class="qty-btn" id="qty-increase" aria-label="Increase quantity">+</button>
                            </div>
                            <button type="button" class="btn btn-primary add-to-cart-btn" id="add-to-cart"<?php echo $in_stock ? '' : ' disabled'; ?>>Add to Cart</button>
                        </div>
                        <div class="product-shipping-info" style="margin-top:2rem;padding-top:2rem;border-top:1px solid #eee">
                            <h2 style="font-size:1.25rem;margin-bottom:1rem;font-weight:600">Shipping Details</h2>
                            <div style="display:flex;flex-direction:column;gap:0.75rem">
                                <div style="display:flex;align-items:flex-start;gap:0.5rem">
                                    <svg viewBox="0 0 24 24" width="20" height="20" style="flex-shrink:0" aria-hidden="true"><path fill="currentColor" d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11.8h2c0 1.7 1.3 3 3 3s3-1.3 3-3h6c0 1.7 1.3 3 3 3s3-1.3 3-3h2v-5l-3-4z"/></svg>
                                    <div>
                                        <strong>Delivery Fee:</strong> <?php echo htmlspecialchars($delivery_fee_display['symbol'] . $delivery_fee_display['formatted']); ?>
                                        <?php if (CURRENCY_IS_NGN): ?><br><span style="color:#666;font-size:0.9rem"><?php echo htmlspecialchars(puppiary_delivery_window_text()); ?> · Lagos delivery</span><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <p>Browse our <a href="/products">puppy product catalog</a> to find what you need.</p>
            <?php endif; ?>
        </section>
    </main>
<?php
$footer_scripts = '<script>window.products = ' . json_encode($products, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';</script><script>var products = window.products || [];</script>';
require __DIR__ . '/includes/footer.php';
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var container = document.getElementById('product-detail');
    if (!container) return;

    var slugParam = container.getAttribute('data-product-slug');
    var product = null;
    if (slugParam && typeof products !== 'undefined') {
        product = products.find(function(p) { return p.slug === slugParam; });
    }
    if (!product) {
        var path = window.location.pathname;
        var pathParts = path.split('/').filter(function(part) { return part; });
        var slugFromPath = pathParts.length > 1 && pathParts[0] === 'product' ? pathParts[1] : null;
        var urlParams = new URLSearchParams(window.location.search);
        slugParam = slugFromPath || urlParams.get('slug');
        var idParam = urlParams.get('id');
        if (slugParam && typeof products !== 'undefined') {
            product = products.find(function(p) { return p.slug === slugParam; });
        }
        if (!product && idParam && typeof products !== 'undefined') {
            product = products.find(function(p) { return p.id === parseInt(idParam, 10); });
        }
    }

    if (!product || product.published === false) return;
    if (typeof trackViewItem === 'function') trackViewItem(product);

    var mainImg = document.getElementById('main-product-image');
    var thumbs = container.querySelectorAll('.product-thumbnail');
    thumbs.forEach(function(thumb, i) {
        thumb.addEventListener('click', function() {
            if (mainImg) mainImg.src = product.images[i];
            thumbs.forEach(function(t) { t.classList.remove('active'); });
            thumb.classList.add('active');
        });
    });

    var qtyInput = document.getElementById('quantity');
    var qtyDec = document.getElementById('qty-decrease');
    var qtyInc = document.getElementById('qty-increase');
    if (qtyDec && qtyInput) qtyDec.addEventListener('click', function() { var v = parseInt(qtyInput.value, 10) || 1; if (v > 1) qtyInput.value = v - 1; });
    if (qtyInc && qtyInput) qtyInc.addEventListener('click', function() { var v = parseInt(qtyInput.value, 10) || 1; var max = product.stock || 999; if (v < max) qtyInput.value = v + 1; });

    var addBtn = document.getElementById('add-to-cart');
    if (addBtn && typeof addToCart === 'function') {
        addBtn.addEventListener('click', function() {
            var qty = parseInt(qtyInput.value, 10) || 1;
            addToCart(product, qty);
            addBtn.textContent = 'Added!';
            setTimeout(function() { addBtn.textContent = 'Add to Cart'; }, 2000);
        });
    }
});
</script>
