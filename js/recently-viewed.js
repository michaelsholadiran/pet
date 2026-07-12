// Recently viewed products (localStorage, Jumia-style bar below footer)

const RECENTLY_VIEWED_KEY = 'puppiary-recently-viewed'
const MAX_RECENTLY_VIEWED = 12

function getRecentlyViewedIds() {
  try {
    const raw = localStorage.getItem(RECENTLY_VIEWED_KEY)
    const parsed = raw ? JSON.parse(raw) : []
    return Array.isArray(parsed) ? parsed.filter((id) => Number.isFinite(Number(id))) : []
  } catch (e) {
    return []
  }
}

function saveRecentlyViewedIds(ids) {
  localStorage.setItem(RECENTLY_VIEWED_KEY, JSON.stringify(ids))
}

function addRecentlyViewed(productId) {
  const id = parseInt(productId, 10)
  if (!id) return

  let ids = getRecentlyViewedIds().filter((storedId) => storedId !== id)
  ids.unshift(id)
  if (ids.length > MAX_RECENTLY_VIEWED) {
    ids = ids.slice(0, MAX_RECENTLY_VIEWED)
  }
  saveRecentlyViewedIds(ids)
  renderRecentlyViewed()
}

function getProductFromPath() {
  const path = window.location.pathname || ''
  const parts = path.split('/').filter(Boolean)
  if (parts[0] !== 'product') return null

  const slug = parts[1] || null
  const urlParams = new URLSearchParams(window.location.search)
  const slugParam = slug || urlParams.get('slug')
  const idParam = urlParams.get('id')
  const productsList = window.products || []

  if (slugParam) {
    return productsList.find((p) => p.slug === slugParam && p.published !== false) || null
  }
  if (idParam) {
    return productsList.find((p) => p.id === parseInt(idParam, 10) && p.published !== false) || null
  }
  return null
}

function escapeRecentlyViewedHtml(value) {
  return String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
}

function renderRecentlyViewed() {
  const section = document.getElementById('recently-viewed-section')
  const track = document.getElementById('recently-viewed-track')
  if (!section || !track) return

  const productsList = window.products || []
  const items = getRecentlyViewedIds()
    .map((id) => productsList.find((p) => p.id == id && p.published !== false))
    .filter(Boolean)

  if (items.length === 0) {
    section.hidden = true
    track.innerHTML = ''
    return
  }

  const sym = (typeof window.CURRENCY_SYMBOL !== 'undefined' && window.CURRENCY_SYMBOL) ? window.CURRENCY_SYMBOL : '₦'

  section.hidden = false
  track.innerHTML = items.map((product) => {
    const unitPrice = typeof getProductPrice === 'function'
      ? getProductPrice(product)
      : product.price
    const formatted = typeof formatPrice === 'function'
      ? formatPrice(unitPrice)
      : unitPrice

    return `
      <a href="/product/${encodeURIComponent(product.slug)}" class="recently-viewed-card" data-product-id="${product.id}">
        <div class="recently-viewed-image-wrap">
          <img src="${escapeRecentlyViewedHtml(product.images[0])}" alt="${escapeRecentlyViewedHtml(product.name)}" loading="lazy" decoding="async" width="120" height="120">
        </div>
        <span class="recently-viewed-name">${escapeRecentlyViewedHtml(product.name)}</span>
        <span class="recently-viewed-price">${escapeRecentlyViewedHtml(sym + formatted)}</span>
      </a>
    `
  }).join('')
}

function recordRecentlyViewedFromPage() {
  const product = getProductFromPath()
  if (product) {
    addRecentlyViewed(product.id)
  }
}

document.addEventListener('DOMContentLoaded', () => {
  recordRecentlyViewedFromPage()
  renderRecentlyViewed()
})
