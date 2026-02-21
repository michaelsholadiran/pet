// Cart Page Logic

document.addEventListener("DOMContentLoaded", () => {
  // Only run on cart page
  const cartContent = document.getElementById("cart-content")
  if (!cartContent) {
    return // Exit early if not on cart page
  }

  // Track view cart event
  const cart = getCart()
  if (typeof trackViewCart === 'function' && cart.length > 0) {
    trackViewCart(cart)
  }

  renderCart()

  // Re-render cart on window resize to switch between mobile/desktop layouts
  let resizeTimeout
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout)
    resizeTimeout = setTimeout(() => {
      renderCart()
    }, 250) // Debounce resize events
  })
})

function renderCart() {
  const cart = getCart()
  const cartContent = document.getElementById("cart-content")

  if (!cartContent) {
    return // Silently exit if not on cart page
  }

  if (cart.length === 0) {
    cartContent.innerHTML = `
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <p>Your cart is empty</p>
                <a href="/products" class="btn btn-primary">Continue Shopping</a>
            </div>
        `
    return
  }

  // Check if mobile view (screen width <= 768px)
  const isMobile = window.innerWidth <= 768

  let cartHTML
  let total = 0

  if (isMobile) {
    // Mobile card layout
    cartHTML = '<div class="cart-items-mobile">'

    const sym = (typeof window.CURRENCY_SYMBOL !== 'undefined' && window.CURRENCY_SYMBOL) ? window.CURRENCY_SYMBOL : '₦'
    cart.forEach((item) => {
      const product = products.find((p) => p.id === item.id)
      if (product) {
        const unitPrice = getProductPrice(product)
        const subtotal = unitPrice * item.quantity
        total += subtotal

        cartHTML += `
                <div class="cart-item-card">
                    <div class="cart-item-header">
                        <img src="${product.images[0]}" alt="${product.name}" class="cart-item-image-mobile" loading="lazy" decoding="async" width="80" height="60">
                        <div class="cart-item-info">
                            <h3 class="cart-item-name-mobile">${product.name}</h3>
                            <p class="cart-item-price-mobile">${sym}${formatPrice(unitPrice)}</p>
                        </div>
                        <button class="remove-btn-mobile" onclick="removeFromCart(${product.id}); renderCart();">
                            <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
                                <path fill="currentColor" d="M18.3 5.71 12 12l6.3 6.29-1.41 1.41L10.59 13.4 4.29 19.7 2.88 18.3 9.17 12 2.88 5.71 4.29 4.3l6.3 6.3 6.29-6.3z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="cart-item-footer">
                        <div class="cart-qty-control-mobile">
                            <button onclick="decrementQuantity(${product.id})" class="qty-btn-mobile">−</button>
                            <span class="qty-display">${item.quantity}</span>
                            <button onclick="incrementQuantity(${product.id})" class="qty-btn-mobile">+</button>
                        </div>
                        <div class="cart-item-subtotal">
                            <span>Subtotal: </span>
                            <strong>${sym}${formatPrice(subtotal)}</strong>
                        </div>
                    </div>
                </div>
            `
      }
    })

    cartHTML += "</div>"
  } else {
    // Desktop table layout
    cartHTML =
      '<div class="table-responsive" role="region" aria-label="Shopping cart items" tabindex="0"><table class="cart-table"><thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr></thead><tbody>'

  const sym = (typeof window.CURRENCY_SYMBOL !== 'undefined' && window.CURRENCY_SYMBOL) ? window.CURRENCY_SYMBOL : '₦'
  cart.forEach((item) => {
    const product = products.find((p) => p.id === item.id)
    if (product) {
      const unitPrice = getProductPrice(product)
      const subtotal = unitPrice * item.quantity
      total += subtotal

      cartHTML += `
                <tr>
                    <td>
                        <img src="${product.images[0]}" alt="${product.name}" class="cart-item-image" loading="lazy" decoding="async" width="80" height="60">
                        <span class="cart-item-name">${product.name}</span>
                    </td>
                    <td class="cart-item-price">${sym}${formatPrice(unitPrice)}</td>
                    <td>
                        <div class="cart-qty-control">
                            <button onclick="decrementQuantity(${product.id})">−</button>
                            <span>${item.quantity}</span>
                            <button onclick="incrementQuantity(${product.id})">+</button>
                        </div>
                    </td>
                    <td>${sym}${formatPrice(subtotal)}</td>
                    <td>
                        <button class="remove-btn" onclick="removeFromCart(${product.id}); renderCart();">Remove</button>
                    </td>
                </tr>
            `
    }
  })

  cartHTML += "</tbody></table></div>"
  }

  const deliveryFee = typeof DELIVERY_FEE !== 'undefined' ? DELIVERY_FEE : (window.DELIVERY_FEE != null ? window.DELIVERY_FEE : 4800)
  const grandTotal = total + deliveryFee
  const summarySym = (typeof window.CURRENCY_SYMBOL !== 'undefined' && window.CURRENCY_SYMBOL) ? window.CURRENCY_SYMBOL : '₦'

  const summaryHTML = `
        <div class="cart-summary">
            <div class="cart-summary-row">
                <span>Subtotal:</span>
                <span>${summarySym}${formatPrice(total)}</span>
            </div>
            <div class="cart-summary-row">
                <span>Delivery Fee:</span>
                <span>${summarySym}${formatPrice(deliveryFee)}</span>
            </div>
            <div class="cart-total">
                <strong>Total:</strong>
                <strong>${summarySym}${formatPrice(grandTotal)}</strong>
            </div>
            <a href="/checkout" class="btn btn-primary btn-large" style="width: 100%; margin-top: 1rem; text-align: center;">Proceed to Checkout</a>
            <a href="/products" class="btn btn-secondary btn-large" style="width: 100%; margin-top: 0.75rem; text-align: center;">Continue Shopping</a>
        </div>
    `

  // Wrap in flex container
  cartHTML = `<div class="cart-content-wrapper">${cartHTML}${summaryHTML}</div>`

  cartContent.innerHTML = cartHTML
}

function incrementQuantity(productId) {
  const cart = getCart()
  const item = cart.find((i) => i.id === productId)
  if (item) {
    updateCartItemQuantity(productId, item.quantity + 1)
    renderCart()
  }
}

function decrementQuantity(productId) {
  const cart = getCart()
  const item = cart.find((i) => i.id === productId)
  if (!item) return
  const nextQty = item.quantity - 1
  if (nextQty >= 1) {
    updateCartItemQuantity(productId, nextQty)
    renderCart()
  }
}
