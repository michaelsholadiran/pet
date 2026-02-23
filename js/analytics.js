// Google Analytics DataLayer Helper – e-commerce events with full item details
window.dataLayer = window.dataLayer || [];

var DATA_LAYER_ITEM_LIST_NAME = 'Product List';
var DATA_LAYER_ITEM_BRAND = 'Puppiary';

function pushDataLayer(eventData) {
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({ ecommerce: null });
  window.dataLayer.push(eventData);
}

function getCurrency() {
  return (typeof window.CURRENCY !== 'undefined' && window.CURRENCY) ? window.CURRENCY : 'NGN';
}

function getPriceForDataLayer(product) {
  if (!product) return 0;
  if (typeof window.getProductPrice === 'function') return window.getProductPrice(product);
  return product.price;
}

// Build one item object for dataLayer (item_id, item_name, item_brand, item_category, item_variant, price, quantity)
function buildEcommerceItem(product, quantity) {
  if (!product) return null;
  var qty = typeof quantity === 'number' && quantity > 0 ? quantity : 1;
  return {
    item_id: String(product.id),
    item_name: product.name || '',
    item_brand: product.item_brand != null ? product.item_brand : DATA_LAYER_ITEM_BRAND,
    item_category: product.category || '',
    item_variant: product.item_variant || '',
    price: Number(getPriceForDataLayer(product)),
    quantity: qty
  };
}

// view_item_list: when user sees a list of products
function trackViewItemList(itemListName, productList) {
  if (!productList || !Array.isArray(productList) || productList.length === 0) return;
  var items = productList.map(function(p) { return buildEcommerceItem(p, 1); }).filter(Boolean);
  if (items.length === 0) return;
  pushDataLayer({
    event: 'view_item_list',
    ecommerce: {
      item_list_name: itemListName || DATA_LAYER_ITEM_LIST_NAME,
      items: items
    }
  });
}

// select_item: when user clicks on a product (e.g. from list)
function trackSelectItem(itemListName, product) {
  if (!product) return;
  var item = buildEcommerceItem(product, 1);
  if (!item) return;
  pushDataLayer({
    event: 'select_item',
    ecommerce: {
      item_list_name: itemListName || DATA_LAYER_ITEM_LIST_NAME,
      items: [item]
    }
  });
}

// view_item: product detail page
function trackViewItem(product) {
  if (!product) return;
  var price = getPriceForDataLayer(product);
  var item = buildEcommerceItem(product, 1);
  if (!item) return;
  pushDataLayer({
    event: 'view_item',
    ecommerce: {
      currency: getCurrency(),
      value: price,
      items: [item]
    }
  });
}

// add_to_cart: when user adds a product to cart
function trackAddToCart(product, quantity) {
  if (!product) return;
  var qty = typeof quantity === 'number' && quantity > 0 ? quantity : 1;
  var price = getPriceForDataLayer(product);
  var item = buildEcommerceItem(product, qty);
  if (!item) return;
  pushDataLayer({
    event: 'add_to_cart',
    ecommerce: {
      currency: getCurrency(),
      value: price * qty,
      items: [item]
    }
  });
}

// begin_checkout: when user starts checkout
function trackBeginCheckout(cart, total, deliveryFee, coupon) {
  if (!cart || !Array.isArray(cart) || cart.length === 0) return;
  var productsList = (typeof products !== 'undefined' ? products : null) || (typeof window.products !== 'undefined' ? window.products : []) || [];
  var items = cart.map(function(item) {
    var product = productsList.find(function(p) { return p.id === item.id; });
    return product ? buildEcommerceItem(product, item.quantity) : null;
  }).filter(Boolean);
  if (items.length === 0) return;
  var grandTotal = total + (deliveryFee || 0);
  var payload = {
    event: 'begin_checkout',
    ecommerce: {
      currency: getCurrency(),
      value: grandTotal,
      items: items
    }
  };
  if (coupon) payload.ecommerce.coupon = coupon;
  pushDataLayer(payload);
}

// purchase: when user completes a purchase
function trackPurchase(transactionId, cart, total, deliveryFee, email, options) {
  if (!cart || !Array.isArray(cart) || cart.length === 0) return;
  if (!transactionId) return;
  var productsList = (typeof products !== 'undefined' ? products : null) || (typeof window.products !== 'undefined' ? window.products : []) || [];
  var items = cart.map(function(item) {
    var product = productsList.find(function(p) { return p.id === item.id; });
    return product ? buildEcommerceItem(product, item.quantity) : null;
  }).filter(Boolean);
  if (items.length === 0) return;
  var grandTotal = total + (deliveryFee || 0);
  var payload = {
    event: 'purchase',
    ecommerce: {
      transaction_id: String(transactionId),
      value: grandTotal,
      currency: getCurrency(),
      tax: (options && options.tax != null) ? options.tax : 0,
      shipping: deliveryFee || 0,
      items: items
    }
  };
  if (options && options.affiliation) payload.ecommerce.affiliation = options.affiliation;
  if (options && options.coupon) payload.ecommerce.coupon = options.coupon;
  pushDataLayer(payload);
}

// add_payment_info: when user selects payment method (same ecommerce shape as begin_checkout: currency, value, items)
function trackAddPaymentInfo(paymentMethod, cart, total, deliveryFee, currency, value) {
  if (!cart || !Array.isArray(cart) || cart.length === 0) return;
  var productsList = (typeof products !== 'undefined' ? products : null) || (typeof window.products !== 'undefined' ? window.products : []) || [];
  var items = cart.map(function(item) {
    var product = productsList.find(function(p) { return p.id === item.id; });
    return product ? buildEcommerceItem(product, item.quantity) : null;
  }).filter(Boolean);
  if (items.length === 0) return;
  var val = value != null ? Number(value) : (total + (deliveryFee || 0));
  var curr = currency || getCurrency();
  pushDataLayer({
    event: 'add_payment_info',
    payment_method: paymentMethod,
    ecommerce: {
      currency: curr,
      value: val,
      items: items
    }
  });
}

// view_cart: cart page (optional; keep for consistency)
function trackViewCart(cart) {
  if (!cart || !Array.isArray(cart) || cart.length === 0) return;
  var productsList = (typeof products !== 'undefined' ? products : null) || (typeof window.products !== 'undefined' ? window.products : []) || [];
  var items = cart.map(function(item) {
    var product = productsList.find(function(p) { return p.id === item.id; });
    return product ? buildEcommerceItem(product, item.quantity) : null;
  }).filter(Boolean);
  if (items.length === 0) return;
  var total = items.reduce(function(sum, it) { return sum + (it.price * it.quantity); }, 0);
  pushDataLayer({
    event: 'view_cart',
    ecommerce: {
      currency: getCurrency(),
      value: total,
      items: items
    }
  });
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

document.addEventListener('DOMContentLoaded', function() {
  trackPageView();
});
