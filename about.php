<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'About Us - Puppiary';
$page_description = 'Learn about Puppiary - your trusted partner for puppy and dog care essentials. Quality, safety, and genuine pet-parent support.';
$page_canonical = '/about';
$current_nav = 'about';
$json_ld_scripts = [
    ['@context' => 'https://schema.org', '@type' => 'AboutPage', 'name' => $page_title, 'url' => SITE_URL . '/about', 'description' => $page_description]
];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="about-section">
            <h1>🐾 About Puppiary</h1>
            <div class="about-content">
                <article>
                    <h2>Our Story: The Joy of New Beginnings</h2>
                    <p>Puppiary was founded by pet lovers who deeply understand the excitement (and the chaos!) of bringing a new puppy home. We realized that new pet parents need more than just products; they need guidance, trust, and peace of mind.</p>
                    <p>What began as a mission to curate the safest, highest-quality starter essentials has grown into a thriving community. We are here to support you through every wagging tail, every puppy-dog eye, and every chewed shoe, ensuring your journey as a pet parent is joyful and stress-free.</p>
                </article>
                <article>
                    <h2>Our Mission: Easing the Puppy Journey</h2>
                    <p>We are committed to being your most trusted resource for puppy and dog care. We achieve this through:</p>
                    <ul>
                        <li><strong>Paws-itively Tested Quality:</strong> Every item is rigorously selected for safety, durability, and health benefits. If it's not good enough for our own pups, it's not good enough for yours.</li>
                        <li><strong>Expertly Curated Solutions:</strong> We don't just stock products; we provide solutions—from anxiety aids to teething triumphs—designed to solve real challenges new pet owners face.</li>
                        <li><strong>Genuine Pet-Parent Support:</strong> Our team is staffed by fellow dog enthusiasts ready to offer friendly, knowledgeable advice, making sure you never feel alone in your journey.</li>
                    </ul>
                </article>
                <article>
                    <h2>Why Choose Puppiary?</h2>
                    <p>Choosing Puppiary means choosing a partner committed to your dog's happy, healthy start.</p>
                    <ul>
                        <li><strong>Trusted, Vet-Approved Essentials:</strong> A curated collection focused on the specific needs of puppies and young dogs.</li>
                        <li><strong>Guiding You Home:</strong> Specialized "Starter Kits" and guides to make your first weeks seamless.</li>
                        <li><strong>Fast & Reliable Puppy-Packs:</strong> Quick shipping and simple, no-hassle returns.</li>
                        <li><strong>The Puppiary Promise:</strong> Fair and transparent pricing with a clear commitment to integrity and care.</li>
                    </ul>
                </article>
                <article>
                    <h2>Our Values</h2>
                    <p>Trust, Safety, and Genuine Care are the pillars of the Puppiary community. We believe in building a lasting relationship with you and your dog, one based on high quality and the shared joy of unconditional love.</p>
                </article>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
