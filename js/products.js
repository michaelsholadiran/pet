// Product Array
const products = [
  {
    id: 1,
    name: "Durable Rubber Chew Bone",
    category: "Teething & Chew Destruction",
    price: 24.99,
    shortDescription: "Non-toxic rubber bone for teething puppies",
    description:
      "Made from 100% food-grade rubber, this durable chew bone is perfect for teething puppies. Designed to soothe sore gums while redirecting destructive chewing away from furniture. Non-toxic and dishwasher safe for easy cleaning.",
    images: [
      "https://picsum.photos/seed/dog-chew-bone/800/600",
      "https://picsum.photos/seed/dog-chew-bone-a/800/600",
      "https://picsum.photos/seed/dog-chew-bone-b/800/600",
      "https://picsum.photos/seed/dog-chew-bone-c/800/600",
      "https://picsum.photos/seed/dog-chew-bone-d/800/600",
      "https://picsum.photos/seed/dog-chew-bone-e/800/600",
      "https://picsum.photos/seed/dog-chew-bone-f/800/600",
      "https://picsum.photos/seed/dog-chew-bone-g/800/600"
    ],
    stock: 15,
  },
  {
    id: 2,
    name: "Puppy Teething Ring Set",
    category: "Teething & Chew Destruction",
    price: 19.99,
    shortDescription: "Set of 3 textured teething rings",
    description:
      "Three different textured teething rings designed to provide relief during the teething phase. Each ring has unique textures to massage gums and keep your puppy engaged. Made from safe, non-toxic materials perfect for puppies 8 weeks and older.",
    images: [
      "https://picsum.photos/seed/puppy-teething-rings/800/600",
      "https://picsum.photos/seed/puppy-teething-rings-a/800/600",
      "https://picsum.photos/seed/puppy-teething-rings-b/800/600",
      "https://picsum.photos/seed/puppy-teething-rings-c/800/600",
      "https://picsum.photos/seed/puppy-teething-rings-d/800/600",
      "https://picsum.photos/seed/puppy-teething-rings-e/800/600",
      "https://picsum.photos/seed/puppy-teething-rings-f/800/600",
      "https://picsum.photos/seed/puppy-teething-rings-g/800/600"
    ],
    stock: 20,
  },
  {
    id: 3,
    name: "Comfort Cuddle Bed",
    category: "Anxiety & Comfort",
    price: 49.99,
    shortDescription: "Soft, cozy bed for anxious puppies",
    description:
      "Ultra-soft cuddle bed designed to provide comfort and security for anxious puppies. Features raised edges that create a safe, den-like environment perfect for separation anxiety. Machine washable cover and orthopedic support for growing pups.",
    images: [
      "https://picsum.photos/seed/puppy-comfort-bed/800/600",
      "https://picsum.photos/seed/puppy-comfort-bed-a/800/600",
      "https://picsum.photos/seed/puppy-comfort-bed-b/800/600",
      "https://picsum.photos/seed/puppy-comfort-bed-c/800/600",
      "https://picsum.photos/seed/puppy-comfort-bed-d/800/600",
      "https://picsum.photos/seed/puppy-comfort-bed-e/800/600",
      "https://picsum.photos/seed/puppy-comfort-bed-f/800/600",
      "https://picsum.photos/seed/puppy-comfort-bed-g/800/600"
    ],
    stock: 12,
  },
  {
    id: 4,
    name: "Anxiety Calming Blanket",
    category: "Anxiety & Comfort",
    price: 34.99,
    shortDescription: "Weighted blanket for separation anxiety",
    description:
      "Specially designed weighted blanket that provides gentle pressure to calm anxious puppies. Perfect for crate training, separation anxiety, and helping puppies sleep through the night. Made from breathable, washable fabric safe for puppies.",
    images: [
      "https://picsum.photos/seed/puppy-anxiety-blanket/800/600",
      "https://picsum.photos/seed/puppy-anxiety-blanket-a/800/600",
      "https://picsum.photos/seed/puppy-anxiety-blanket-b/800/600",
      "https://picsum.photos/seed/puppy-anxiety-blanket-c/800/600",
      "https://picsum.photos/seed/puppy-anxiety-blanket-d/800/600",
      "https://picsum.photos/seed/puppy-anxiety-blanket-e/800/600",
      "https://picsum.photos/seed/puppy-anxiety-blanket-f/800/600",
      "https://picsum.photos/seed/puppy-anxiety-blanket-g/800/600"
    ],
    stock: 18,
  },
  {
    id: 5,
    name: "Adjustable Puppy Harness",
    category: "Walks & Training Control",
    price: 29.99,
    shortDescription: "Safe, adjustable harness for growing pups",
    description:
      "Perfect for first walks and training! This adjustable harness grows with your puppy, featuring multiple sizing points for a secure, comfortable fit. No-pull design protects your puppy's neck and provides better control during training walks.",
    images: [
      "https://picsum.photos/seed/puppy-harness/800/600",
      "https://picsum.photos/seed/puppy-harness-a/800/600",
      "https://picsum.photos/seed/puppy-harness-b/800/600",
      "https://picsum.photos/seed/puppy-harness-c/800/600",
      "https://picsum.photos/seed/puppy-harness-d/800/600",
      "https://picsum.photos/seed/puppy-harness-e/800/600",
      "https://picsum.photos/seed/puppy-harness-f/800/600",
      "https://picsum.photos/seed/puppy-harness-g/800/600"
    ],
    stock: 25,
  },
  {
    id: 6,
    name: "Retractable Training Leash",
    category: "Walks & Training Control",
    price: 39.99,
    shortDescription: "Lightweight leash for puppy training",
    description:
      "Lightweight retractable leash perfect for training walks with small puppies. Features a comfortable grip, smooth retraction, and a safety lock mechanism. Designed specifically for puppies under 25 pounds with gentle guidance.",
    images: [
      "https://picsum.photos/seed/puppy-leash/800/600",
      "https://picsum.photos/seed/puppy-leash-a/800/600",
      "https://picsum.photos/seed/puppy-leash-b/800/600",
      "https://picsum.photos/seed/puppy-leash-c/800/600",
      "https://picsum.photos/seed/puppy-leash-d/800/600",
      "https://picsum.photos/seed/puppy-leash-e/800/600",
      "https://picsum.photos/seed/puppy-leash-f/800/600",
      "https://picsum.photos/seed/puppy-leash-g/800/600"
    ],
    stock: 22,
  },
  {
    id: 7,
    name: "Paw Washer & Dryer",
    category: "Hygiene & Home Cleanliness",
    price: 32.99,
    shortDescription: "Easy-to-use paw cleaning system",
    description:
      "Keep your home clean with this convenient paw washer! Simply fill with water, insert your puppy's paw, and the soft silicone bristles gently remove dirt and mud. Quick-dry design means no more muddy paw prints throughout your house.",
    images: [
      "https://picsum.photos/seed/puppy-paw-washer/800/600",
      "https://picsum.photos/seed/puppy-paw-washer-a/800/600",
      "https://picsum.photos/seed/puppy-paw-washer-b/800/600",
      "https://picsum.photos/seed/puppy-paw-washer-c/800/600",
      "https://picsum.photos/seed/puppy-paw-washer-d/800/600",
      "https://picsum.photos/seed/puppy-paw-washer-e/800/600",
      "https://picsum.photos/seed/puppy-paw-washer-f/800/600",
      "https://picsum.photos/seed/puppy-paw-washer-g/800/600"
    ],
    stock: 16,
  },
  {
    id: 8,
    name: "Puppy Grooming Brush Set",
    category: "Hygiene & Home Cleanliness",
    price: 27.99,
    shortDescription: "Gentle brushes for puppy coat care",
    description:
      "Complete grooming set designed for sensitive puppy skin. Includes a soft-bristle brush for daily grooming, a de-shedding tool for reducing loose hair, and a gentle comb for detangling. Perfect for maintaining a clean, healthy coat while bonding with your pup.",
    images: [
      "https://picsum.photos/seed/puppy-grooming-brush/800/600",
      "https://picsum.photos/seed/puppy-grooming-brush-a/800/600",
      "https://picsum.photos/seed/puppy-grooming-brush-b/800/600",
      "https://picsum.photos/seed/puppy-grooming-brush-c/800/600",
      "https://picsum.photos/seed/puppy-grooming-brush-d/800/600",
      "https://picsum.photos/seed/puppy-grooming-brush-e/800/600",
      "https://picsum.photos/seed/puppy-grooming-brush-f/800/600",
      "https://picsum.photos/seed/puppy-grooming-brush-g/800/600"
    ],
    stock: 14,
  },
  {
    id: 9,
    name: "Slow-Feed Puppy Bowl",
    category: "Health & Feeding",
    price: 18.99,
    shortDescription: "Prevents fast eating and improves digestion",
    description:
      "Designed to slow down fast eaters and prevent digestive issues. The unique maze pattern encourages your puppy to eat at a healthier pace, reducing the risk of bloating and improving nutrient absorption. Made from food-safe, BPA-free materials.",
    images: [
      "https://picsum.photos/seed/puppy-slow-feed-bowl/800/600",
      "https://picsum.photos/seed/puppy-slow-feed-bowl-a/800/600",
      "https://picsum.photos/seed/puppy-slow-feed-bowl-b/800/600",
      "https://picsum.photos/seed/puppy-slow-feed-bowl-c/800/600",
      "https://picsum.photos/seed/puppy-slow-feed-bowl-d/800/600",
      "https://picsum.photos/seed/puppy-slow-feed-bowl-e/800/600",
      "https://picsum.photos/seed/puppy-slow-feed-bowl-f/800/600",
      "https://picsum.photos/seed/puppy-slow-feed-bowl-g/800/600"
    ],
    stock: 20,
  },
  {
    id: 10,
    name: "Puppy Starter Kit",
    category: "Teething & Chew Destruction",
    price: 79.99,
    shortDescription: "Complete teething solution kit",
    description:
      "Everything your new puppy needs for the teething phase! This comprehensive starter kit includes 5 durable chew toys, 2 teething rings, a cooling gel toy, and a training guide. All items are non-toxic, vet-approved, and designed to save your furniture from destructive chewing.",
    images: [
      "https://picsum.photos/seed/puppy-starter-kit/800/600",
      "https://picsum.photos/seed/puppy-starter-kit-a/800/600",
      "https://picsum.photos/seed/puppy-starter-kit-b/800/600",
      "https://picsum.photos/seed/puppy-starter-kit-c/800/600",
      "https://picsum.photos/seed/puppy-starter-kit-d/800/600",
      "https://picsum.photos/seed/puppy-starter-kit-e/800/600",
      "https://picsum.photos/seed/puppy-starter-kit-f/800/600",
      "https://picsum.photos/seed/puppy-starter-kit-g/800/600"
    ],
    stock: 10,
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
