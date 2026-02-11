<?php
$page_title = 'FAQ - Puppiary';
$page_description = 'Find answers to common questions about Puppiary shipping, ordering, returns, guarantees, and product safety for your puppy.';
$page_canonical = '/faq';
$current_nav = 'faq';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="faq-section">
            <h1>Frequently Asked Questions</h1>
            <p>Here are the most common questions our new puppy parents ask. If you don't see your answer here, please contact us!</p>
            <h2>Shipping & Ordering</h2>
            <div class="accordion">
                <div class="accordion-item">
                    <button class="accordion-header" aria-expanded="false">
                        <span>Q: Do you deliver nationwide?</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <p>Yes! We proudly deliver our safe, quality supplies anywhere in Nigeria. Our standard delivery time is 1–4 working days, with expedited options available during checkout for urgent training or teething needs.</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header" aria-expanded="false">
                        <span>Q: Can I pay on delivery (PoD)?</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <p>For your convenience and trust, we offer Cash on Delivery (PoD) in many major metropolitan areas, including Lagos, Abuja, and Port Harcourt. If this option is available in your area, you will see it at checkout.</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header" aria-expanded="false">
                        <span>Q: Where is Puppiary located?</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <p>Puppiary is an online-based pet brand, allowing us to deliver quickly to pups across Nigeria. Our headquarters and primary distribution center are located in Lagos, ensuring efficient nationwide fulfillment.</p>
                    </div>
                </div>
            </div>
            <h2>Returns & Guarantees</h2>
            <div class="accordion">
                <div class="accordion-item">
                    <button class="accordion-header" aria-expanded="false">
                        <span>Q: What if my puppy doesn't like the product?</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <p>We are so confident in the quality and safety of our gear, we offer a 100% Puppy-Approved Money-Back Guarantee. If you or your pup aren't completely satisfied with the fit or function within 7 days of delivery, we guarantee a full refund or a hassle-free exchange. We make the return process risk-free so you can shop with total confidence. Visit our <a href="/refund">Returns page</a> for simple instructions.</p>
                    </div>
                </div>
            </div>
            <h2>Product Questions</h2>
            <div class="accordion">
                <div class="accordion-item">
                    <button class="accordion-header" aria-expanded="false">
                        <span>Q: Is the harness adjustable to accommodate a growing pup?</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <p>Absolutely. Our harnesses are specifically designed for the growing pup's journey. They are fully adjustable and feature multiple custom sizing points, ensuring a secure, comfortable fit from their first walk right up to their transition into adulthood.</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header" aria-expanded="false">
                        <span>Q: How do I clean the paw washer?</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <p>We designed our paw washer for maximum owner convenience! Simply disassemble the unit by removing the soft silicone bristles, rinse all parts with warm water, and let them air dry. It's designed to be quick and easy, making messy paws manageable.</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-header" aria-expanded="false">
                        <span>Q: Are your toys non-toxic and safe for heavy chewers?</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="accordion-content">
                        <p>Yes—safety is our non-negotiable promise. All Puppiary toys are made from non-toxic, pet-safe materials (like food-grade silicone and natural rubber) and are rigorously tested for durability to ensure they withstand the demands of even the most destructive chewers.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php
$footer_scripts = '<script>
document.addEventListener("DOMContentLoaded", function() {
    var accordionHeaders = document.querySelectorAll(".accordion-header");
    accordionHeaders.forEach(function(header) {
        header.addEventListener("click", function() {
            var isOpen = this.getAttribute("aria-expanded") === "true";
            accordionHeaders.forEach(function(h) {
                h.setAttribute("aria-expanded", "false");
                h.parentElement.classList.remove("open");
            });
            if (!isOpen) {
                this.setAttribute("aria-expanded", "true");
                this.parentElement.classList.add("open");
            }
        });
    });
    if (window.SEO && typeof window.SEO.jsonLdFAQ === "function") {
        window.SEO.jsonLdFAQ([
            { question: "Do you deliver nationwide?", answer: "Yes! We proudly deliver our safe, quality supplies anywhere in Nigeria. Our standard delivery time is 1–4 working days, with expedited options available during checkout for urgent training or teething needs." },
            { question: "Can I pay on delivery (PoD)?", answer: "For your convenience and trust, we offer Cash on Delivery (PoD) in many major metropolitan areas, including Lagos, Abuja, and Port Harcourt. If this option is available in your area, you will see it at checkout." },
            { question: "Where is Puppiary located?", answer: "Puppiary is an online-based pet brand, allowing us to deliver quickly to pups across Nigeria. Our headquarters and primary distribution center are located in Lagos, ensuring efficient nationwide fulfillment." },
            { question: "What if my puppy doesn\'t like the product?", answer: "We are so confident in the quality and safety of our gear, we offer a 100% Puppy-Approved Money-Back Guarantee. If you or your pup aren\'t completely satisfied with the fit or function within 7 days of delivery, we guarantee a full refund or a hassle-free exchange." },
            { question: "Is the harness adjustable to accommodate a growing pup?", answer: "Absolutely. Our harnesses are specifically designed for the growing pup\'s journey. They are fully adjustable and feature multiple custom sizing points, ensuring a secure, comfortable fit from their first walk right up to their transition into adulthood." },
            { question: "How do I clean the paw washer?", answer: "We designed our paw washer for maximum owner convenience! Simply disassemble the unit by removing the soft silicone bristles, rinse all parts with warm water, and let them air dry. It\'s designed to be quick and easy, making messy paws manageable." },
            { question: "Are your toys non-toxic and safe for heavy chewers?", answer: "Yes—safety is our non-negotiable promise. All Puppiary toys are made from non-toxic, pet-safe materials (like food-grade silicone and natural rubber) and are rigorously tested for durability to ensure they withstand the demands of even the most destructive chewers." }
        ]);
    }
});
</script>';
require __DIR__ . '/includes/footer.php';
?>
