// Product Array
const products = [
  {
    id: 1,
    name: "Indestructible Chew Toy",
    category: "Play & Teething",
    price: 8200,
    shortDescription: "Durable, non-toxic chew toy for teething puppies",
    description:
      "Made from 100% food-grade rubber, this indestructible chew toy is perfect for teething puppies. Designed to soothe sore gums while redirecting destructive chewing away from furniture. Non-toxic and dishwasher safe for easy cleaning.",
    images: [
      "/products/indestructible-chew-toy/indestructible-chew-toy-1.jpg",
      "/products/indestructible-chew-toy/indestructible-chew-toy-2.jpg",
      "/products/indestructible-chew-toy/indestructible-chew-toy-3.jpg",
      "/products/indestructible-chew-toy/indestructible-chew-toy-4.jpg",
      "/products/indestructible-chew-toy/indestructible-chew-toy-5.jpg",
      "/products/indestructible-chew-toy/indestructible-chew-toy-6.jpg",
      "/products/indestructible-chew-toy/indestructible-chew-toy-7.jpg",
      "/products/indestructible-chew-toy/indestructible-chew-toy-8.jpg"
    ],
    stock: 15,
  },
  {
    id: 2,
    name: "Calming Dog Bed",
    category: "Grooming & Comfort",
    price: 44000,
    shortDescription: "Soft, cozy bed for anxious puppies",
    description:
      "Ultra-soft calming bed designed to provide comfort and security for anxious puppies. Features raised edges that create a safe, den-like environment perfect for separation anxiety. Machine washable cover and orthopedic support for growing pups.",
    images: [
      "/products/calming-dog-bed/calming-dog-bed-1.jpg",
      "/products/calming-dog-bed/calming-dog-bed-2.jpg",
      "/products/calming-dog-bed/calming-dog-bed-3.png",
      "/products/calming-dog-bed/calming-dog-bed-4.jpg",
      "/products/calming-dog-bed/calming-dog-bed-5.jpg",
      "/products/calming-dog-bed/calming-dog-bed-6.png",
      "/products/calming-dog-bed/calming-dog-bed-7.png",
      "/products/calming-dog-bed/calming-dog-bed-8.png"
    ],
    stock: 12,
  },
  {
    id: 3,
    name: "No-Pull Harness",
    category: "Training & Safety",
    price: 12200,
    shortDescription: "Safe, adjustable no-pull harness for growing pups",
    description:
      "Perfect for first walks and training! This adjustable no-pull harness grows with your puppy, featuring multiple sizing points for a secure, comfortable fit. No-pull design protects your puppy's neck and provides better control during training walks.",
    images: [
      "/products/no-pull-harness/no-pull-harness-1.jpg",
      "/products/no-pull-harness/no-pull-harness-2.jpg",
      "/products/no-pull-harness/no-pull-harness-3.jpg",
      "/products/no-pull-harness/no-pull-harness-4.png",
      "/products/no-pull-harness/no-pull-harness-5.png",
      "/products/no-pull-harness/no-pull-harness-6.png",
      "/products/no-pull-harness/no-pull-harness-7.png",
      "/products/no-pull-harness/no-pull-harness-8.png"
    ],
    stock: 25,
  },
  {
    id: 4,
    name: "Dog Paw Washer Cup",
    category: "Grooming & Comfort",
    price: 10200,
    shortDescription: "Easy-to-use paw cleaning system",
    description:
      "Keep your home clean with this convenient paw washer cup! Simply fill with water, insert your puppy's paw, and the soft silicone bristles gently remove dirt and mud. Quick-dry design means no more muddy paw prints throughout your house.",
    images: [
      "/products/dog-paw-washer-cup/dog-paw-washer-cup-1.jpg",
      "/products/dog-paw-washer-cup/dog-paw-washer-cup-2.png",
      "/products/dog-paw-washer-cup/dog-paw-washer-cup-3.png",
      "/products/dog-paw-washer-cup/dog-paw-washer-cup-4.png",
      "/products/dog-paw-washer-cup/dog-paw-washer-cup-5.png"
    ],
    stock: 16,
  },
  {
    id: 5,
    name: "Grooming Glove",
    category: "Grooming & Comfort",
    price: 9000,
    shortDescription: "Gentle grooming glove for puppy coat care",
    description:
      "Complete grooming glove designed for sensitive puppy skin. The soft, flexible design allows for comfortable grooming while removing loose hair and dirt. Perfect for maintaining a clean, healthy coat while bonding with your pup.",
    images: [
      "/products/grooming-glove/grooming-glove-1.avif",
      "/products/grooming-glove/grooming-glove-2.avif",
      "/products/grooming-glove/grooming-glove-3.jpg",
      "/products/grooming-glove/grooming-glove-4.jpg",
      "/products/grooming-glove/grooming-glove-5.png",
      "/products/grooming-glove/grooming-glove-6.png",
      "/products/grooming-glove/grooming-glove-7.png",
      "/products/grooming-glove/grooming-glove-8.png"
    ],
    stock: 14,
  },
  {
    id: 6,
    name: "Feeding Bowl",
    category: "Feeding",
    price: 8000,
    shortDescription: "Durable feeding bowl for puppies",
    description:
      "Designed for healthy feeding habits. The unique design encourages your puppy to eat at a comfortable pace. Made from food-safe, BPA-free materials that are easy to clean and dishwasher safe.",
    images: [
      "/products/feeding-bowl/feeding-bowl-1.jpg",
      "/products/feeding-bowl/feeding-bowl-2.png",
      "/products/feeding-bowl/feeding-bowl-3.png",
      "/products/feeding-bowl/feeding-bowl-4.png"
    ],
    stock: 20,
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
                <span class="product-card-price">₦${formatPrice(product.price)}</span>
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
    window.location.href = `/product/${product.slug}`
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
