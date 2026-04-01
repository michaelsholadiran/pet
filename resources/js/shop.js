// Cart & Shop Utilities - global for add-to-cart, formatPrice, etc.
function getCart() {
  const cart = localStorage.getItem('puppiary-cart');
  return cart ? JSON.parse(cart) : [];
}

function refreshCartViews() {
  renderCart();
  renderCartDrawer();
}

function saveCart(cart) {
  localStorage.setItem('puppiary-cart', JSON.stringify(cart));
  updateCartCounter();
  refreshCartViews();
}

function addToCart(product, quantity = 1) {
  const cart = getCart();
  const existingItem = cart.find((item) => item.id === product.id);
  if (existingItem) {
    existingItem.quantity += quantity;
  } else {
    cart.push({ id: product.id, quantity });
  }
  saveCart(cart);
  if (typeof trackAddToCart === 'function') trackAddToCart(product, quantity);
}

/** @param {Array<{ quantity: number, product: object }>} lines */
function reorderProducts(lines) {
  if (!Array.isArray(lines)) return;
  lines.forEach((line) => {
    if (line && line.product && typeof addToCart === 'function') {
      const qty = Math.max(1, parseInt(line.quantity, 10) || 1);
      addToCart(line.product, qty);
    }
  });
}

function removeFromCart(productId) {
  saveCart(getCart().filter((item) => item.id !== productId));
}

function updateCartItemQuantity(productId, quantity) {
  const cart = getCart();
  const item = cart.find((item) => item.id === productId);
  if (item) {
    item.quantity = quantity;
    if (item.quantity <= 0) removeFromCart(productId);
    else saveCart(cart);
  }
}

function getProductPrice(product) {
  if (!product) return 0;
  const isNGN = typeof window.CURRENCY !== 'undefined' && window.CURRENCY === 'NGN';
  if (isNGN) return product.price;
  return product.price_usd != null ? product.price_usd : product.price / 1500;
}

function formatPrice(price) {
  const isNGN = typeof window.CURRENCY !== 'undefined' && window.CURRENCY === 'NGN';
  return Number(price).toLocaleString(isNGN ? 'en-NG' : 'en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
}

function updateCartCounter() {
  const total = getCart().reduce((sum, item) => sum + item.quantity, 0);
  document.querySelectorAll('.cart-counter').forEach((el) => {
    el.textContent = total;
  });
}

document.addEventListener('click', (e) => {
  const btn = e.target.closest('.add-to-cart-btn');
  if (!btn) return;
  const id = btn.getAttribute('data-product-id');
  if (!id) return;
  e.preventDefault();
  e.stopPropagation();
  const product = (window.products || []).find((p) => p.id == id);
  if (product && typeof addToCart === 'function') {
    const qty = parseInt(btn.getAttribute('data-quantity') || '1', 10) || 1;
    addToCart(product, qty);
    btn.textContent = 'Added!';
    setTimeout(() => (btn.textContent = 'Add to Cart'), 1500);
  }
});

document.addEventListener('DOMContentLoaded', () => {
  updateCartCounter();
  renderCartDrawer();
  if (document.body?.dataset?.openCart === '1') {
    openCartDrawer();
    delete document.body.dataset.openCart;
  }
});

document.addEventListener('click', (e) => {
  const btn = e.target.closest('[data-puppiary-reorder]');
  if (!btn) return;
  let lines = [];
  try {
    lines = JSON.parse(btn.getAttribute('data-puppiary-reorder') || '[]');
  } catch {
    return;
  }
  if (typeof reorderProducts === 'function') {
    reorderProducts(lines);
  }
  if (typeof openCartDrawer === 'function') {
    openCartDrawer();
  }
  const dest = btn.getAttribute('data-puppiary-reorder-redirect');
  if (dest) {
    window.location.href = dest;
  }
});

document.addEventListener('click', (e) => {
  const trigger = e.target.closest('.js-cart-trigger');
  if (!trigger) return;
  if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;
  e.preventDefault();
  if (typeof openCartDrawer === 'function') openCartDrawer();
});

// Cart page helpers (global for onclick in rendered HTML)
function incrementQuantity(productId) {
  const cart = getCart();
  const item = cart.find((i) => i.id === productId);
  if (item) {
    updateCartItemQuantity(productId, item.quantity + 1);
  }
}

function decrementQuantity(productId) {
  const cart = getCart();
  const item = cart.find((i) => i.id === productId);
  if (!item) return;
  if (item.quantity > 1) {
    updateCartItemQuantity(productId, item.quantity - 1);
  }
}

function renderCart() {
  const cartContent = document.getElementById('cart-content');
  if (!cartContent) return;
  const cart = getCart();
  const products = window.products || [];
  const sym = window.CURRENCY_SYMBOL || '₦';
  const isUsd = typeof window.CURRENCY !== 'undefined' && window.CURRENCY === 'USD';
  const freeShippingThreshold = isUsd ? 100 : 100000;
  const deliveryFeeBase = window.DELIVERY_FEE ?? 4800;
  const taxRate = 0.08;

  if (cart.length === 0) {
    cartContent.innerHTML = `
      <div class="text-center py-16">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        <h2 class="text-2xl font-semibold text-gray-700 mb-2">Your cart is empty</h2>
        <p class="text-gray-500 mb-6">Looks like you have not added anything yet.</p>
        <a href="/products" class="inline-block bg-primary text-white px-6 py-3 rounded-full font-semibold no-underline hover:bg-primary-dark transition">
          Continue Shopping →
        </a>
      </div>
    `;
    return;
  }

  let total = 0;
  const isMobile = window.innerWidth <= 900;
  let itemsHtml = '';

  cart.forEach((item) => {
    const product = products.find((p) => p.id === item.id);
    if (!product) return;
    const unitPrice = getProductPrice(product);
    const subtotal = unitPrice * item.quantity;
    total += subtotal;
    const sizeLabel = product.size || product.variant || product.category || 'Standard';
    if (isMobile) {
      itemsHtml += `
        <div class="p-6 flex flex-wrap gap-4 border-b border-gray-200">
          <img src="${product.images?.[0] || ''}" alt="${product.name}" class="w-24 h-24 rounded-lg object-cover" loading="lazy">
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900">${product.name}</h3>
            <p class="text-sm text-gray-500">${sizeLabel}</p>
            <div class="flex items-center gap-3 mt-2">
              <div class="flex items-center border border-gray-300 rounded-full overflow-hidden">
                <button onclick="decrementQuantity(${product.id})" class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition rounded-none">−</button>
                <span class="w-10 text-center">${item.quantity}</span>
                <button onclick="incrementQuantity(${product.id})" class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition rounded-none">+</button>
              </div>
              <button onclick="removeFromCart(${product.id});renderCart();" class="text-red-500 text-sm hover:text-red-700 transition">Remove</button>
            </div>
          </div>
          <div class="text-right">
            <p class="font-bold text-gray-900">${sym}${formatPrice(subtotal)}</p>
            <p class="text-sm text-gray-400">${sym}${formatPrice(unitPrice)} each</p>
          </div>
        </div>
      `;
    } else {
      itemsHtml += `
        <div class="p-6 flex flex-wrap md:flex-nowrap gap-4 border-b border-gray-200">
          <img src="${product.images?.[0] || ''}" alt="${product.name}" class="w-24 h-24 rounded-lg object-cover" loading="lazy">
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900">${product.name}</h3>
            <p class="text-sm text-gray-500">${sizeLabel}</p>
            <div class="flex items-center gap-3 mt-2">
              <div class="flex items-center border border-gray-300 rounded-full overflow-hidden">
                <button onclick="decrementQuantity(${product.id})" class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition rounded-none">−</button>
                <span class="w-10 text-center">${item.quantity}</span>
                <button onclick="incrementQuantity(${product.id})" class="px-3 py-1 text-gray-600 hover:bg-gray-100 transition rounded-none">+</button>
              </div>
              <button onclick="removeFromCart(${product.id});renderCart();" class="text-red-500 text-sm hover:text-red-700 transition">Remove</button>
            </div>
          </div>
          <div class="text-right">
            <p class="font-bold text-gray-900">${sym}${formatPrice(subtotal)}</p>
            <p class="text-sm text-gray-400">${sym}${formatPrice(unitPrice)} each</p>
          </div>
        </div>
      `;
    }
  });

  const shipping = total > freeShippingThreshold ? 0 : deliveryFeeBase;
  const tax = total * taxRate;
  const grandTotal = total + shipping + tax;
  const progressPct = Math.min(100, (total / freeShippingThreshold) * 100);
  const amountToFreeShipping = Math.max(0, freeShippingThreshold - total);

  cartContent.innerHTML = `
    <div class="grid lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
          ${itemsHtml}
        </div>
        <div class="mt-4">
          <a href="/products" class="inline-flex items-center gap-2 text-primary hover:text-primary-dark transition no-underline">
            ← Continue Shopping
          </a>
        </div>
      </div>
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24">
          <h2 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h2>
          <div class="space-y-3 mb-4">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Subtotal</span>
              <span class="font-medium">${sym}${formatPrice(total)}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Shipping</span>
              <span class="${shipping === 0 ? 'text-green-600' : ''}">${shipping === 0 ? 'Free' : `${sym}${formatPrice(shipping)}`}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Tax (estimated)</span>
              <span>${sym}${formatPrice(tax)}</span>
            </div>
            <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
              <span>Total</span>
              <span class="text-primary">${sym}${formatPrice(grandTotal)}</span>
            </div>
          </div>
          ${total < freeShippingThreshold ? `
          <div class="mb-4">
            <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
              <div class="bg-primary h-2 rounded-full" style="width:${progressPct}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Add ${sym}${formatPrice(amountToFreeShipping)} more for free shipping</p>
          </div>` : ''}
          <a href="/checkout" class="block w-full bg-primary text-white py-3 rounded-full font-semibold hover:bg-primary-dark transition text-center no-underline">
            Proceed to Checkout →
          </a>
          <div class="mt-4 text-center text-xs text-gray-500 space-y-1">
            <p>30-day happiness guarantee</p>
            <p>Secure checkout • SSL encrypted</p>
          </div>
        </div>
      </div>
    </div>
  `;
}

function renderCartDrawer() {
  const scrollEl = document.getElementById('cart-drawer-scroll');
  const footerEl = document.getElementById('cart-drawer-footer');
  if (!scrollEl) return;
  const cart = getCart();
  const products = window.products || [];
  const sym = window.CURRENCY_SYMBOL || '₦';
  const isUsd = typeof window.CURRENCY !== 'undefined' && window.CURRENCY === 'USD';
  const freeShippingThreshold = isUsd ? 100 : 100000;
  const deliveryFeeBase = window.DELIVERY_FEE ?? 4800;
  const taxRate = 0.08;

  if (footerEl) {
    footerEl.classList.toggle('hidden', cart.length === 0);
    if (cart.length === 0) footerEl.innerHTML = '';
  }

  if (cart.length === 0) {
    scrollEl.innerHTML = `
      <div class="text-center py-10 px-2">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        <p class="text-gray-700 font-medium mb-1">Your cart is empty</p>
        <p class="text-sm text-gray-500 mb-6">Add something your pup will love.</p>
        <a href="/products" class="inline-block bg-primary text-white px-5 py-2.5 rounded-full font-semibold text-sm no-underline hover:bg-primary-dark transition">
          Shop products
        </a>
      </div>
    `;
    return;
  }

  let total = 0;
  let itemsHtml = '';

  cart.forEach((item) => {
    const product = products.find((p) => p.id === item.id);
    if (!product) return;
    const unitPrice = getProductPrice(product);
    const subtotal = unitPrice * item.quantity;
    total += subtotal;
    const sizeLabel = product.size || product.variant || product.category || 'Standard';
    const img = product.images?.[0] || '';
    itemsHtml += `
      <div class="flex gap-3 py-4 border-b border-gray-100 first:pt-0">
        <img src="${img}" alt="" class="w-16 h-16 rounded-lg object-cover shrink-0" loading="lazy">
        <div class="flex-1 min-w-0">
          <h3 class="font-medium text-gray-900 text-sm leading-snug">${product.name}</h3>
          <p class="text-xs text-gray-500">${sizeLabel}</p>
          <div class="flex items-center gap-2 mt-2">
            <div class="flex items-center border border-gray-200 rounded-full overflow-hidden text-sm">
              <button type="button" onclick="decrementQuantity(${product.id})" class="px-2 py-1 text-gray-600 hover:bg-gray-50 rounded-none">−</button>
              <span class="w-8 text-center">${item.quantity}</span>
              <button type="button" onclick="incrementQuantity(${product.id})" class="px-2 py-1 text-gray-600 hover:bg-gray-50 rounded-none">+</button>
            </div>
            <button type="button" onclick="removeFromCart(${product.id})" class="text-xs text-red-600 hover:text-red-800">Remove</button>
          </div>
        </div>
        <div class="text-right shrink-0">
          <p class="text-sm font-semibold text-gray-900">${sym}${formatPrice(subtotal)}</p>
          <p class="text-xs text-gray-400">${sym}${formatPrice(unitPrice)} ea.</p>
        </div>
      </div>
    `;
  });

  const shipping = total > freeShippingThreshold ? 0 : deliveryFeeBase;
  const tax = total * taxRate;
  const grandTotal = total + shipping + tax;
  const progressPct = Math.min(100, (total / freeShippingThreshold) * 100);
  const amountToFreeShipping = Math.max(0, freeShippingThreshold - total);

  scrollEl.innerHTML = `<div class="space-y-0">${itemsHtml}</div>`;

  if (footerEl) {
    footerEl.innerHTML = `
    <div class="space-y-2 text-sm">
      <div class="flex justify-between">
        <span class="text-gray-600">Subtotal</span>
        <span class="font-medium">${sym}${formatPrice(total)}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-600">Shipping</span>
        <span class="${shipping === 0 ? 'text-green-600 font-medium' : ''}">${shipping === 0 ? 'Free' : `${sym}${formatPrice(shipping)}`}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-600">Tax (est.)</span>
        <span>${sym}${formatPrice(tax)}</span>
      </div>
      <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-100">
        <span>Total</span>
        <span class="text-primary">${sym}${formatPrice(grandTotal)}</span>
      </div>
    </div>
    ${
      total < freeShippingThreshold
        ? `
    <div class="mt-3 mb-1">
      <div class="bg-gray-200 rounded-full h-1.5 overflow-hidden">
        <div class="bg-primary h-1.5 rounded-full" style="width:${progressPct}%"></div>
      </div>
      <p class="text-xs text-gray-500 mt-1.5">${sym}${formatPrice(amountToFreeShipping)} more for free shipping</p>
    </div>`
        : ''
    }
    <a href="/checkout" class="mt-4 block w-full bg-primary text-white py-3 rounded-full font-semibold text-center text-sm no-underline hover:bg-primary-dark transition">
      Checkout
    </a>
    <a href="/products" class="mt-2.5 block text-center text-sm text-primary font-medium no-underline hover:underline pb-0.5">
      Continue shopping
    </a>
  `;
  }
}

function openCartDrawer() {
  window.dispatchEvent(new CustomEvent('puppiary-cart-open'));
  renderCartDrawer();
}

window.getCart = getCart;
window.saveCart = saveCart;
window.addToCart = addToCart;
window.removeFromCart = removeFromCart;
window.updateCartItemQuantity = updateCartItemQuantity;
window.reorderProducts = reorderProducts;
window.updateCartCounter = updateCartCounter;
window.formatPrice = formatPrice;
window.getProductPrice = getProductPrice;
window.renderCart = renderCart;
window.renderCartDrawer = renderCartDrawer;
window.openCartDrawer = openCartDrawer;
window.incrementQuantity = incrementQuantity;
window.decrementQuantity = decrementQuantity;
window.refreshCartViews = refreshCartViews;
