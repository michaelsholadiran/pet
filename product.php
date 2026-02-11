<?php
require_once __DIR__ . '/includes/products_data.php';
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$page_title = 'Product - Puppiary';
$page_description = 'Shop quality puppy and dog products at Puppiary.';
$page_canonical = '/product' . ($slug ? '/' . $slug : '');
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="product-detail" id="product-detail">
            <p>Loading...</p>
        </section>
    </main>
<?php
// Product page uses JS to render; load product detail script from original product.html
$footer_scripts = '<script>window.products = ' . json_encode($products, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';</script><script>var products = window.products || [];</script>';
require __DIR__ . '/includes/footer.php';
?>
<!-- Product detail page script (must run after window.products) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var path = window.location.pathname;
    var pathParts = path.split('/').filter(function(part) { return part; });
    var slugFromPath = pathParts.length > 1 && pathParts[0] === 'product' ? pathParts[1] : null;
    var urlParams = new URLSearchParams(window.location.search);
    var slugParam = slugFromPath || urlParams.get('slug');
    var idParam = urlParams.get('id');
    var productId = idParam ? parseInt(idParam, 10) : null;
    var product = null;
    if (slugParam && typeof products !== 'undefined') {
        product = products.find(function(p) { return p.slug === slugParam; });
    }
    if (!product && productId && typeof products !== 'undefined') {
        product = products.find(function(p) { return p.id === productId; });
    }
    var container = document.getElementById('product-detail');
    if (!container) return;
    if (!product) {
        container.innerHTML = '<p>Product not found.</p>';
        return;
    }
    if (product.published === false) {
        container.innerHTML = '<p>Product not found.</p>';
        return;
    }
    if (typeof trackViewItem === 'function') trackViewItem(product);
    var thumbnails = product.images.map(function(img, index) {
        return '<img src="' + img + '" alt="' + (product.name) + '" class="product-thumbnail ' + (index === 0 ? 'active' : '') + '" data-image-index="' + index + '" loading="lazy" decoding="async">';
    }).join('');
    var detailHTML = '<div class="product-detail-container">' +
        '<div class="product-image-section">' +
        '<img src="' + product.images[0] + '" alt="' + (product.name) + '" class="product-detail-image" id="main-product-image" loading="eager" decoding="async" fetchpriority="high" width="800" height="600">' +
        '<div class="product-thumbnails">' + thumbnails + '</div></div>' +
        '<div class="product-info-section">' +
        '<h1>' + product.name + '</h1>' +
        '<p class="product-category">Category: ' + product.category + '</p>' +
        '<p class="product-price">₦' + (typeof formatPrice === 'function' ? formatPrice(product.price) : product.price.toLocaleString()) + '</p>' +
        '<p class="product-stock ' + (product.stock > 0 ? 'in-stock' : 'out-of-stock') + '">' + (product.stock > 0 ? product.stock + ' in stock' : 'Out of stock') + '</p>' +
        '<p class="product-description">' + product.description + '</p>' +
        '<div class="product-actions">' +
        '<div class="quantity-selector">' +
        '<button class="qty-btn" id="qty-decrease">−</button>' +
        '<input type="number" id="quantity" value="1" min="1" max="' + product.stock + '" aria-label="Quantity">' +
        '<button class="qty-btn" id="qty-increase">+</button></div>' +
        '<button class="btn btn-primary add-to-cart-btn" id="add-to-cart"' + (product.stock === 0 ? ' disabled' : '') + '>Add to Cart</button></div>' +
        '<div class="product-shipping-info" style="margin-top:2rem;padding-top:2rem;border-top:1px solid #eee">' +
        '<h3 style="font-size:1.25rem;margin-bottom:1rem;font-weight:600">Shipping Details</h3>' +
        '<div style="display:flex;flex-direction:column;gap:0.75rem">' +
        '<div style="display:flex;align-items:flex-start;gap:0.5rem"><svg viewBox="0 0 24 24" width="20" height="20" style="flex-shrink:0" aria-hidden="true"><path fill="currentColor" d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11.8h2c0 1.7 1.3 3 3 3s3-1.3 3-3h6c0 1.7 1.3 3 3 3s3-1.3 3-3h2v-5l-3-4z"/></svg><div><strong>Delivery Fee:</strong> ₦' + (typeof formatPrice === 'function' && typeof DELIVERY_FEE !== 'undefined' ? formatPrice(DELIVERY_FEE) : '4,800.00') + '<br><span style="color:#666;font-size:0.9rem">Standard delivery within Lagos</span></div></div></div></div></div></div>';
    container.innerHTML = detailHTML;
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
    if (qtyDec) qtyDec.addEventListener('click', function() { var v = parseInt(qtyInput.value, 10) || 1; if (v > 1) qtyInput.value = v - 1; });
    if (qtyInc) qtyInc.addEventListener('click', function() { var v = parseInt(qtyInput.value, 10) || 1; var max = product.stock || 999; if (v < max) qtyInput.value = v + 1; });
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
