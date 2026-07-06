<?php
/** Product card add-to-cart / quantity controls. Expects $p with id. */
$card_product_id = (int) $p['id'];
?>
<div class="product-card-actions" data-product-id="<?php echo $card_product_id; ?>">
    <button type="button" class="btn btn-primary add-to-cart-btn" data-product-id="<?php echo $card_product_id; ?>">Add to Cart</button>
    <div class="product-card-qty" hidden>
        <button type="button" class="product-card-qty-btn product-card-qty-minus" data-product-id="<?php echo $card_product_id; ?>" aria-label="Decrease quantity">−</button>
        <span class="product-card-qty-value" aria-live="polite">1</span>
        <button type="button" class="product-card-qty-btn product-card-qty-plus" data-product-id="<?php echo $card_product_id; ?>" aria-label="Increase quantity">+</button>
    </div>
</div>
