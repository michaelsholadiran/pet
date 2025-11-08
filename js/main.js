// Utility Functions

// Get cart from localStorage
function getCart() {
  const cart = localStorage.getItem("brandly-cart")
  return cart ? JSON.parse(cart) : []
}

// Save cart to localStorage
function saveCart(cart) {
  localStorage.setItem("brandly-cart", JSON.stringify(cart))
  updateCartCounter()
}

// Clear cart
function clearCart() {
  localStorage.removeItem("brandly-cart")
  updateCartCounter()
}

// Add item to cart
function addToCart(product, quantity = 1) {
  const cart = getCart()
  const existingItem = cart.find((item) => item.id === product.id)

  if (existingItem) {
    existingItem.quantity += quantity
  } else {
    cart.push({ id: product.id, quantity })
  }

  saveCart(cart)
}

// Remove item from cart
function removeFromCart(productId) {
  let cart = getCart()
  cart = cart.filter((item) => item.id !== productId)
  saveCart(cart)
}

// Update cart item quantity
function updateCartItemQuantity(productId, quantity) {
  const cart = getCart()
  const item = cart.find((item) => item.id === productId)
  if (item) {
    item.quantity = quantity
    if (item.quantity <= 0) {
      removeFromCart(productId)
    } else {
      saveCart(cart)
    }
  }
}

// Update cart counter in navbar
function updateCartCounter() {
  const cart = getCart()
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0)
  document.querySelectorAll(".cart-counter").forEach((counter) => {
    counter.textContent = totalItems
  })
}

// Initialize
document.addEventListener("DOMContentLoaded", () => {
  updateCartCounter()
  // Mobile drawer (global)
  const menuButton = document.querySelector(".mobile-menu-button")
  const drawer = document.getElementById("mobile-drawer")
  const overlay = document.querySelector("[data-overlay]")
  const closeButton = document.querySelector(".drawer-close")

  function openDrawer() {
    if (!drawer || !menuButton || !overlay) return
    drawer.classList.add("open")
    drawer.setAttribute("aria-hidden", "false")
    menuButton.setAttribute("aria-expanded", "true")
    overlay.hidden = false
    document.body.classList.add("no-scroll")
  }

  function closeDrawer() {
    if (!drawer || !menuButton || !overlay) return
    drawer.classList.remove("open")
    drawer.setAttribute("aria-hidden", "true")
    menuButton.setAttribute("aria-expanded", "false")
    overlay.hidden = true
    document.body.classList.remove("no-scroll")
  }

  if (menuButton && drawer && overlay && closeButton) {
    menuButton.addEventListener("click", openDrawer)
    closeButton.addEventListener("click", closeDrawer)
    overlay.addEventListener("click", closeDrawer)
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && drawer.classList.contains("open")) {
        closeDrawer()
      }
    })
  }
})
