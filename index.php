<?php
$page_title = 'Puppy Toys, Teething & Starter Kits | Non-Toxic Supplies | Puppiary';
$page_description = 'The ultimate resource for new puppy parents. Shop durable chew toys, training gear, and comfort essentials designed to solve teething pain and separation anxiety.';
$page_canonical = '/';
$page_keywords = 'puppy toys, puppy chew toys, puppy teething toys, puppy starter kits, non-toxic puppy toys, puppy supplies, puppy training gear';
$body_class = 'home';
$extra_head = '    <link rel="preload" href="/images/indestructible-chew-toy/indestructible-chew-toy-1.jpg" as="image" fetchpriority="high">';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="promo-banner" aria-label="Featured Products">
            <div class="promo-banner-container">
                <div class="promo-banner-image">
                    <img src="/images/puppiary-homepage-promotional-banner.webp" alt="Three dogs laying down on calming comfort bed - Puppiary" loading="eager" decoding="async" fetchpriority="high">
                </div>
                <div class="promo-banner-content">
                    <h2 class="promo-banner-headline">New Year's Sale is here!</h2>
                    <p class="promo-banner-description">Enjoy special discounts on select products - Calming Dog Bed, No-Pull Harness, and Grooming Glove. Limited time only.</p>
                    <div class="promo-banner-actions">
                        <a href="/products" class="btn btn-promo-primary">Shop</a>
                        <a href="/about" class="btn btn-promo-secondary">Learn More</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="promo-banner" aria-label="No-Pull Harness">
            <div class="promo-banner-container">
                <div class="promo-banner-image">
                    <img src="/images/puppy-wearing-our-no-pull-harness.webp" alt="Puppy wearing No-Pull Harness" loading="lazy" decoding="async">
                </div>
                <div class="promo-banner-content">
                    <h2 class="promo-banner-headline">Walk with Confidence!</h2>
                    <p class="promo-banner-description">Our No-Pull Harness gives you better control during walks while keeping your puppy safe and comfortable. Perfect for training and growing pups.</p>
                    <div class="promo-banner-actions">
                        <a href="/product/no-pull-harness" class="btn btn-promo-primary">Shop</a>
                        <a href="/products" class="btn btn-promo-secondary">View All Products</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
