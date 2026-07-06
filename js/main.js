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
  if (typeof renderCart === "function") renderCart()
  if (typeof syncProductCardActions === "function") syncProductCardActions()
}

// Clear cart
function clearCart() {
  localStorage.removeItem("puppiary-cart")
  updateCartCounter()
  if (typeof syncProductCardActions === "function") syncProductCardActions()
}

// Add item to cart
function addToCart(product, quantity = 1, options = {}) {
  const cart = getCart()
  const existingItem = cart.find((item) => item.id === product.id)
  const isNewItem = !existingItem

  if (existingItem) {
    existingItem.quantity += quantity
  } else {
    cart.push({ id: product.id, quantity })
  }

  saveCart(cart)

  if (isNewItem && typeof trackAddToCart === "function") {
    trackAddToCart(product, quantity)
  }

  if (options.openDrawer !== false && typeof openCartDrawer === "function") {
    openCartDrawer()
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

function getCartQuantity(productId) {
  const item = getCart().find((i) => i.id == productId)
  return item ? item.quantity : 0
}

function syncProductCardActions() {
  document.querySelectorAll(".product-card-actions[data-product-id]").forEach((actions) => {
    const productId = parseInt(actions.dataset.productId, 10)
    const qty = getCartQuantity(productId)
    const addBtn = actions.querySelector(".add-to-cart-btn")
    const qtyWrap = actions.querySelector(".product-card-qty")
    const qtyVal = actions.querySelector(".product-card-qty-value")
    if (!addBtn || !qtyWrap) return

    if (qty > 0) {
      addBtn.hidden = true
      qtyWrap.hidden = false
      if (qtyVal) qtyVal.textContent = qty
    } else {
      addBtn.hidden = false
      qtyWrap.hidden = true
    }
  })
}

function findProductById(productId) {
  return (window.products || []).find((p) => p.id == productId)
}

function getCatalogProducts() {
  return (window.products || []).filter((p) => {
    if (p.published === false) return false
    if (p.list_in_catalog === false) return false
    return true
  })
}

function addStarterKitToCart() {
  const catalog = getCatalogProducts()
  if (!catalog.length) return

  let cart = getCart()
  catalog.forEach((product) => {
    const existing = cart.find((item) => item.id === product.id)
    if (existing) {
      existing.quantity += 1
    } else {
      cart.push({ id: product.id, quantity: 1 })
      if (typeof trackAddToCart === "function") {
        trackAddToCart(product, 1)
      }
    }
  })

  saveCart(cart)
  showStarterKitToast()

  if (typeof openCartDrawer === "function") {
    openCartDrawer()
  }
}

function showStarterKitToast() {
  if (typeof Toastify !== "function") return

  Toastify({
    text: "Starter Kit added to cart 🐶",
    duration: 3500,
    gravity: "top",
    position: "center",
    className: "puppiary-toast",
    stopOnFocus: true,
    style: {
      background: "#e38106",
      color: "#ffffff",
      borderRadius: "9999px",
      fontSize: "0.95rem",
      fontWeight: "600",
      padding: "12px 22px",
      boxShadow: "0 8px 24px rgba(0, 0, 0, 0.18)",
    },
  }).showToast()
}

// Product card cart controls
document.addEventListener("click", (e) => {
  const starterBtn = e.target.closest(".starter-kit-btn")
  if (starterBtn) {
    e.preventDefault()
    addStarterKitToCart()
    const label = starterBtn.textContent
    starterBtn.textContent = "Added!"
    starterBtn.disabled = true
    setTimeout(() => {
      starterBtn.textContent = label
      starterBtn.disabled = false
    }, 2000)
    return
  }

  const plusBtn = e.target.closest(".product-card-qty-plus")
  const minusBtn = e.target.closest(".product-card-qty-minus")

  if (plusBtn || minusBtn) {
    e.preventDefault()
    e.stopPropagation()
    const productId = parseInt((plusBtn || minusBtn).dataset.productId, 10)
    const product = findProductById(productId)
    if (!product) return

    const cart = getCart()
    const item = cart.find((i) => i.id == productId)

    if (plusBtn) {
      if (item) {
        updateCartItemQuantity(productId, item.quantity + 1)
      } else {
        addToCart(product, 1, { openDrawer: false })
      }
      return
    }

    if (minusBtn && item) {
      if (item.quantity <= 1) {
        removeFromCart(productId)
      } else {
        updateCartItemQuantity(productId, item.quantity - 1)
      }
    }
    return
  }

  const btn = e.target.closest(".add-to-cart-btn")
  if (!btn) return
  const id = btn.getAttribute("data-product-id")
  if (!id) return
  if (!btn.closest(".product-card-actions")) return
  e.preventDefault()
  e.stopPropagation()
  const product = findProductById(id)
  if (product && typeof addToCart === "function") {
    addToCart(product, 1)
  }
})

// Initialize
document.addEventListener("DOMContentLoaded", () => {
  updateCartCounter()
  syncProductCardActions()

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
    drawer.inert = false
    drawer.classList.add("open")
    drawer.setAttribute("aria-hidden", "false")
    menuButton.setAttribute("aria-expanded", "true")
    overlay.hidden = false
    document.body.classList.add("no-scroll")
    closeButton?.focus()
  }

  function closeDrawer() {
    if (!drawer || !menuButton || !overlay) return
    drawer.classList.remove("open")
    drawer.setAttribute("aria-hidden", "true")
    drawer.inert = true
    menuButton.setAttribute("aria-expanded", "false")
    overlay.hidden = true
    document.body.classList.remove("no-scroll")
    menuButton.focus()
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
