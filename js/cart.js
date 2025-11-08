// Cart Page Logic

document.addEventListener("DOMContentLoaded", () => {
  renderCart()
})

function renderCart() {
  const cart = getCart()
  const cartContent = document.getElementById("cart-content")

  if (cart.length === 0) {
    cartContent.innerHTML = `
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <p>Your cart is empty</p>
                <a href="products.html" class="btn btn-primary">Continue Shopping</a>
            </div>
        `
    return
  }

  let cartHTML =
    '<div class="table-responsive" role="region" aria-label="Shopping cart items" tabindex="0"><table class="cart-table"><thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr></thead><tbody>'
  let total = 0

  cart.forEach((item) => {
    const product = products.find((p) => p.id === item.id)
    if (product) {
      const subtotal = product.price * item.quantity
      total += subtotal

      cartHTML += `
                <tr>
                    <td>
                        <img src="${product.images[0]}" alt="${product.name}" class="cart-item-image" loading="lazy" decoding="async" width="80" height="60">
                        <span class="cart-item-name">${product.name}</span>
                    </td>
                    <td class="cart-item-price">₦${product.price.toFixed(2)}</td>
                    <td>
                        <div class="cart-qty-control">
                            <button onclick="decrementQuantity(${product.id})">−</button>
                            <span>${item.quantity}</span>
                            <button onclick="incrementQuantity(${product.id})">+</button>
                        </div>
                    </td>
                    <td>₦${subtotal.toFixed(2)}</td>
                    <td>
                        <button class="remove-btn" onclick="removeFromCart(${product.id}); renderCart();">Remove</button>
                    </td>
                </tr>
            `
    }
  })

  cartHTML += "</tbody></table></div>"

  cartHTML += `
        <div class="cart-summary">
            <div class="cart-summary-row">
                <span>Subtotal:</span>
                <span>₦${total.toFixed(2)}</span>
            </div>
            <div class="cart-summary-row">
                <span>Shipping:</span>
                <span>Free</span>
            </div>
            <div class="cart-summary-row">
                <span>Tax:</span>
                <span>Calculated at checkout</span>
            </div>
            <div class="cart-total">
                <strong>Total:</strong>
                <strong>₦${total.toFixed(2)}</strong>
            </div>
            <a href="checkout.html" class="btn btn-primary btn-large" style="width: 100%; margin-top: 1rem; text-align: center;">Proceed to Checkout</a>
            <a href="products.html" class="btn btn-secondary btn-large" style="width: 100%; margin-top: 0.75rem; text-align: center;">Continue Shopping</a>
        </div>
    `

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
