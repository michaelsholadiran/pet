// Product Array
const products = [
  {
    id: 1,
    name: "Wireless Headphones",
    category: "Electronics",
    price: 129.99,
    shortDescription: "Premium sound quality with noise cancellation",
    description:
      "Experience crystal-clear audio with our premium wireless headphones. Features active noise cancellation, 30-hour battery life, and premium comfort padding for extended use.",
    images: ["https://picsum.photos/seed/product1/800/600"],
    stock: 15,
  },
  {
    id: 2,
    name: "Leather Crossbody Bag",
    category: "Accessories",
    price: 79.99,
    shortDescription: "Elegant and versatile everyday bag",
    description:
      "Handcrafted from premium Italian leather, this crossbody bag combines style and functionality. Perfect for daily use with multiple compartments and adjustable strap.",
    images: ["https://picsum.photos/seed/product2/800/600"],
    stock: 8,
  },
  {
    id: 3,
    name: "Organic Cotton T-Shirt",
    category: "Apparel",
    price: 34.99,
    shortDescription: "Soft, breathable, and eco-friendly",
    description:
      "Made from 100% organic cotton, this t-shirt is comfortable for everyday wear. Available in multiple colors with sustainable production practices.",
    images: ["https://picsum.photos/seed/product3/800/600"],
    stock: 25,
  },
  {
    id: 4,
    name: "Ceramic Mug Set",
    category: "Home",
    price: 44.99,
    shortDescription: "Handmade ceramic mugs (set of 2)",
    description:
      "Beautiful handcrafted ceramic mugs perfect for your morning coffee or tea. Each set includes 2 mugs with unique glazed patterns.",
    images: ["https://picsum.photos/seed/product4/800/600"],
    stock: 12,
  },
  {
    id: 5,
    name: "Stainless Steel Water Bottle",
    category: "Accessories",
    price: 39.99,
    shortDescription: "Keeps drinks cold for 24 hours",
    description:
      "Double-wall insulated water bottle maintains temperature for up to 24 hours. Eco-friendly alternative with premium stainless steel construction.",
    images: ["https://picsum.photos/seed/product5/800/600"],
    stock: 20,
  },
  {
    id: 6,
    name: "Desk Lamp with USB Charger",
    category: "Home",
    price: 59.99,
    shortDescription: "Modern design with built-in charging port",
    description:
      "Sleek desk lamp featuring adjustable brightness, warm LED light, and an integrated USB charging port for your devices.",
    images: ["https://picsum.photos/seed/product6/800/600"],
    stock: 10,
  },
  {
    id: 7,
    name: "Premium Sunglasses",
    category: "Accessories",
    price: 149.99,
    shortDescription: "UV protection with trendy frames",
    description:
      "Stylish sunglasses with 100% UV protection. Classic design with polarized lenses that reduce glare and provide crystal-clear vision.",
    images: ["https://picsum.photos/seed/product7/800/600"],
    stock: 6,
  },
  {
    id: 8,
    name: "Bamboo Cutting Board",
    category: "Home",
    price: 29.99,
    shortDescription: "Sustainable kitchen essential",
    description:
      "Eco-friendly bamboo cutting board perfect for food prep. Naturally antimicrobial with a smooth, durable surface that's easy to clean.",
    images: ["https://picsum.photos/seed/product8/800/600"],
    stock: 18,
  },
  {
    id: 9,
    name: "Cashmere Scarf",
    category: "Apparel",
    price: 89.99,
    shortDescription: "Luxuriously soft and warm",
    description:
      "Premium 100% cashmere scarf with a timeless design. Incredibly soft and warm, perfect for any season and occasion.",
    images: ["https://picsum.photos/seed/product9/800/600"],
    stock: 5,
  },
  {
    id: 10,
    name: "Wireless Charger",
    category: "Electronics",
    price: 49.99,
    shortDescription: "Fast charging for all Qi-enabled devices",
    description:
      "Convenient wireless charging pad supports all Qi-enabled devices. Fast charging technology with safety certification.",
    images: ["https://picsum.photos/seed/product10/800/600"],
    stock: 22,
  },
]

// Slug helper and assignment
function generateSlug(text) {
  return String(text)
    .toLowerCase()
    .trim()
    .replace(/&/g, " and ")
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "")
}

products.forEach(function (p) {
  if (!p.slug) {
    p.slug = generateSlug(p.name)
  }
})

// addToCart is provided globally by js/main.js

// Create product card element
function createProductCard(product) {
  const card = document.createElement("div")
  card.className = "product-card"
  card.innerHTML = `
        <img src="${product.images[0]}" alt="${product.name}" class="product-card-image" loading="lazy" decoding="async" width="800" height="600">
        <div class="product-card-content">
            <h3 class="product-card-name">${product.name}</h3>
            <p class="product-card-category">${product.category}</p>
            <p class="product-card-description">${product.shortDescription}</p>
            <div class="product-card-footer">
                <span class="product-card-price">₦${product.price.toFixed(2)}</span>
                <div class="product-card-actions">
                    <button class="btn btn-primary add-to-cart-btn" data-id="${product.id}" style="flex: 1;">Add</button>
                </div>
            </div>
        </div>
    `

  // Make entire card act as a link to the product detail
  card.setAttribute("role", "link")
  card.tabIndex = 0
  const navigateToDetail = () => {
    window.location.href = `product.html?slug=${product.slug}`
  }
  card.addEventListener("click", navigateToDetail)
  card.addEventListener("keydown", (e) => {
    if (e.key === "Enter" || e.key === " ") {
      e.preventDefault()
      navigateToDetail()
    }
  })

  card
    .querySelector(".add-to-cart-btn")
    .addEventListener("click", function (event) {
    // Prevent card navigation when clicking Add
    event.stopPropagation()
    addToCart(product, 1)
    this.textContent = "Added!"
    setTimeout(() => {
      this.textContent = "Add"
    }, 1500)
    })

  return card
}
