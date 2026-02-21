// Utility Functions

// Get cart from localStorage
function getCart() {
  const cart = localStorage.getItem("puppiary-cart")
  return cart ? JSON.parse(cart) : []
}

// Save cart to localStorage
function saveCart(cart) {
  localStorage.setItem("puppiary-cart", JSON.stringify(cart))
  updateCartCounter()
}

// Clear cart
function clearCart() {
  localStorage.removeItem("puppiary-cart")
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

  // Track add to cart event
  if (typeof trackAddToCart === 'function') {
    trackAddToCart(product, quantity)
  }
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

// Delivery fee (set by PHP from head; fallback for NGN)
const DELIVERY_FEE = typeof window.DELIVERY_FEE !== 'undefined' ? window.DELIVERY_FEE : 4800

// Effective product price for current currency (NGN or USD)
function getProductPrice(product) {
  if (!product) return 0
  const isNGN = (typeof window.CURRENCY !== 'undefined' && window.CURRENCY === 'NGN')
  if (isNGN) return product.price
  return (product.price_usd != null ? product.price_usd : (product.price / 1500))
}

// Format price with commas (respects window.CURRENCY)
function formatPrice(price) {
  const isNGN = (typeof window.CURRENCY !== 'undefined' && window.CURRENCY === 'NGN')
  return price.toLocaleString(isNGN ? 'en-NG' : 'en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })
}

// Update cart counter in navbar
function updateCartCounter() {
  const cart = getCart()
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0)
  document.querySelectorAll(".cart-counter").forEach((counter) => {
    counter.textContent = totalItems
  })
}

// Add to Cart: delegated handler for .add-to-cart-btn (PHP-rendered or JS-rendered cards)
document.addEventListener("click", (e) => {
  const btn = e.target.closest(".add-to-cart-btn")
  if (!btn) return
  const id = btn.getAttribute("data-product-id")
  if (!id) return
  e.preventDefault()
  e.stopPropagation()
  const products = window.products || []
  const product = products.find((p) => p.id == id)
  if (product && typeof addToCart === "function") {
    addToCart(product, 1)
    btn.textContent = "Added!"
    setTimeout(() => {
      btn.textContent = "Add to Cart"
    }, 1500)
  }
})

// Initialize
document.addEventListener("DOMContentLoaded", () => {
  updateCartCounter()

  // Enhanced sticky navbar
  const navbar = document.querySelector('.navbar')
  if (navbar) {
    let lastScrollY = window.scrollY

    function updateNavbar() {
      const currentScrollY = window.scrollY

      if (currentScrollY > 50) {
        navbar.classList.add('navbar-scrolled')
      } else {
        navbar.classList.remove('navbar-scrolled')
      }

      lastScrollY = currentScrollY
    }

    window.addEventListener('scroll', updateNavbar)
    updateNavbar() // Initial call
  }

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
