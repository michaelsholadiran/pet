// Slide-in cart drawer

function openCartDrawer() {
  const drawer = document.getElementById('cart-drawer')
  const overlay = document.querySelector('[data-cart-overlay]')
  const closeBtn = document.querySelector('.cart-drawer-close')
  if (!drawer || !overlay) return

  renderCart()

  const cart = getCart()
  if (typeof trackViewCart === 'function' && cart.length > 0) {
    trackViewCart(cart)
  }

  drawer.inert = false
  drawer.classList.add('open')
  drawer.setAttribute('aria-hidden', 'false')
  overlay.hidden = false
  document.body.classList.add('no-scroll')
  const cartToggle = document.getElementById('cart-toggle')
  if (cartToggle) cartToggle.setAttribute('aria-expanded', 'true')
  closeBtn?.focus()
}

function closeCartDrawer() {
  const drawer = document.getElementById('cart-drawer')
  const overlay = document.querySelector('[data-cart-overlay]')
  const cartToggle = document.getElementById('cart-toggle')
  if (!drawer || !overlay) return

  drawer.classList.remove('open')
  drawer.setAttribute('aria-hidden', 'true')
  drawer.inert = true
  overlay.hidden = true
  document.body.classList.remove('no-scroll')
  if (cartToggle) cartToggle.setAttribute('aria-expanded', 'false')
  cartToggle?.focus()
}

function renderCart() {
  const itemsEl = document.getElementById('cart-drawer-items')
  const summaryEl = document.getElementById('cart-drawer-summary')
  if (!itemsEl || !summaryEl) return

  const cart = getCart()
  const productsList = window.products || []

  if (cart.length === 0) {
    itemsEl.innerHTML = `
      <div class="empty-cart">
        <div class="empty-cart-icon">🛒</div>
        <p>Your cart is empty</p>
        <button type="button" class="btn btn-primary" onclick="closeCartDrawer()">Continue Shopping</button>
      </div>
    `
    summaryEl.hidden = true
    summaryEl.innerHTML = ''
    return
  }

  let itemsHTML = '<div class="cart-items-mobile cart-drawer-item-list">'
  let total = 0
  const sym = (typeof window.CURRENCY_SYMBOL !== 'undefined' && window.CURRENCY_SYMBOL) ? window.CURRENCY_SYMBOL : '₦'

  cart.forEach((item) => {
    const product = productsList.find((p) => p.id === item.id)
    if (!product) return

    const unitPrice = getProductPrice(product)
    const subtotal = unitPrice * item.quantity
    total += subtotal

    itemsHTML += `
      <div class="cart-item-card">
        <div class="cart-item-header">
          <img src="${product.images[0]}" alt="${product.name}" class="cart-item-image-mobile" loading="lazy" decoding="async" width="80" height="60">
          <div class="cart-item-info">
            <h3 class="cart-item-name-mobile">${product.name}</h3>
            <p class="cart-item-price-mobile">${sym}${formatPrice(unitPrice)}</p>
          </div>
          <button type="button" class="remove-btn-mobile" onclick="removeFromCart(${product.id}); renderCart();" aria-label="Remove ${product.name}">
            <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
              <path fill="currentColor" d="M18.3 5.71 12 12l6.3 6.29-1.41 1.41L10.59 13.4 4.29 19.7 2.88 18.3 9.17 12 2.88 5.71 4.29 4.3l6.3 6.3 6.29-6.3z"/>
            </svg>
          </button>
        </div>
        <div class="cart-item-footer">
          <div class="cart-qty-control-mobile">
            <button type="button" onclick="decrementQuantity(${product.id})" class="qty-btn-mobile" aria-label="Decrease quantity">−</button>
            <span class="qty-display">${item.quantity}</span>
            <button type="button" onclick="incrementQuantity(${product.id})" class="qty-btn-mobile" aria-label="Increase quantity">+</button>
          </div>
          <div class="cart-item-subtotal">
            <span>Subtotal: </span>
            <strong>${sym}${formatPrice(subtotal)}</strong>
          </div>
        </div>
      </div>
    `
  })

  itemsHTML += '</div>'
  itemsEl.innerHTML = itemsHTML

  const deliveryFee = typeof DELIVERY_FEE !== 'undefined' ? DELIVERY_FEE : (window.DELIVERY_FEE != null ? window.DELIVERY_FEE : 4800)
  const grandTotal = total + deliveryFee

  summaryEl.hidden = false
  summaryEl.innerHTML = `
    <div class="cart-summary cart-drawer-summary">
      <div class="cart-summary-row">
        <span>Subtotal:</span>
        <span>${sym}${formatPrice(total)}</span>
      </div>
      <div class="cart-summary-row">
        <span>Delivery Fee:</span>
        <span>${sym}${formatPrice(deliveryFee)}</span>
      </div>
      <div class="cart-total">
        <strong>Total:</strong>
        <strong>${sym}${formatPrice(grandTotal)}</strong>
      </div>
      <a href="/checkout" class="btn btn-primary btn-large cart-drawer-checkout">Proceed to Checkout</a>
      <button type="button" class="btn btn-secondary btn-large cart-drawer-continue" onclick="closeCartDrawer()">Continue Shopping</button>
    </div>
  `
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

document.addEventListener('DOMContentLoaded', () => {
  const cartToggle = document.getElementById('cart-toggle')
  const overlay = document.querySelector('[data-cart-overlay]')
  const closeBtn = document.querySelector('.cart-drawer-close')
  const drawer = document.getElementById('cart-drawer')

  if (cartToggle) {
    cartToggle.addEventListener('click', (e) => {
      e.preventDefault()
      openCartDrawer()
    })
  }

  if (overlay) {
    overlay.addEventListener('click', closeCartDrawer)
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', closeCartDrawer)
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && drawer?.classList.contains('open')) {
      closeCartDrawer()
    }
  })

  if (document.body.dataset.openCart === 'true') {
    openCartDrawer()
  }
})
