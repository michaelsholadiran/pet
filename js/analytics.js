// Google Analytics DataLayer Helper
window.dataLayer = window.dataLayer || [];

// Helper function to push events to dataLayer
function pushDataLayer(eventData) {
  window.dataLayer.push(eventData);
}

// Pageview event
function trackPageView(pageTitle, pagePath) {
  pushDataLayer({
    event: 'page_view',
    page_title: pageTitle || document.title,
    page_path: pagePath || window.location.pathname,
    page_location: window.location.href
  });
}

// Add to cart event
function trackAddToCart(product, quantity = 1) {
  if (!product) return;
  
  pushDataLayer({
    event: 'add_to_cart',
    ecommerce: {
      currency: 'NGN',
      value: product.price * quantity,
      items: [
        {
          item_id: product.id.toString(),
          item_name: product.name,
          item_category: product.category,
          price: product.price,
          quantity: quantity
        }
      ]
    }
  });
}

// Begin checkout event
function trackBeginCheckout(cart, total, deliveryFee) {
  if (!cart || !Array.isArray(cart) || cart.length === 0) return;
  
  const grandTotal = total + deliveryFee;
  const items = cart.map(item => {
    if (typeof products === 'undefined') return null;
    const product = products.find(p => p.id === item.id);
    if (!product) return null;
    return {
      item_id: product.id.toString(),
      item_name: product.name,
      item_category: product.category,
      price: product.price,
      quantity: item.quantity
    };
  }).filter(Boolean);

  if (items.length === 0) return;

  pushDataLayer({
    event: 'begin_checkout',
    ecommerce: {
      currency: 'NGN',
      value: grandTotal,
      items: items
    }
  });
}

// Purchase event
function trackPurchase(transactionId, cart, total, deliveryFee, email) {
  if (!cart || !Array.isArray(cart) || cart.length === 0) return;
  if (!transactionId) return;
  
  const grandTotal = total + deliveryFee;
  const items = cart.map(item => {
    if (typeof products === 'undefined') return null;
    const product = products.find(p => p.id === item.id);
    if (!product) return null;
    return {
      item_id: product.id.toString(),
      item_name: product.name,
      item_category: product.category,
      price: product.price,
      quantity: item.quantity
    };
  }).filter(Boolean);

  if (items.length === 0) return;

  pushDataLayer({
    event: 'purchase',
    ecommerce: {
      transaction_id: transactionId,
      value: grandTotal,
      currency: 'NGN',
      tax: 0,
      shipping: deliveryFee,
      items: items
    }
  });
}

// Track pageview on page load
document.addEventListener('DOMContentLoaded', function() {
  trackPageView();
});
