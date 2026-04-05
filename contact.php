<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Contact Us - Puppiary';
$page_description = "Questions or feedback? Get in touch with the Puppiary team — we're here to help.";
$page_canonical = '/contact';
$current_nav = 'contact';
$json_ld_scripts = [
    ['@context' => 'https://schema.org', '@type' => 'ContactPage', 'name' => $page_title, 'url' => SITE_URL . '/contact', 'description' => $page_description],
    ['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => SITE_NAME, 'url' => SITE_URL, 'contactPoint' => ['@type' => 'ContactPoint', 'telephone' => '+2347016426458', 'contactType' => 'customer service', 'email' => 'hello@puppiary.com', 'availableLanguage' => 'English']]
];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="contact-section">
            <h1>Get in Touch</h1>
            <p class="contact-intro">We'd love to hear from you! Whether you have a question, feedback, or just want to say hello, feel free to reach out.</p>
            <div class="contact-card">
                <h2>Send us a message</h2>
                <p>Reach out to us via email and we'll get right back to you.</p>
                <div class="direct-email">
                    <a href="mailto:hello@puppiary.com" class="email-link">hello@puppiary.com</a>
                </div>
                <div class="divider"><span>or</span></div>
                <form id="contact-form" class="contact-form">
                    <div class="form-group">
                        <label for="contact-name">Name *</label>
                        <input type="text" id="contact-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="contact-email">Email *</label>
                        <input type="email" id="contact-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="contact-subject">Subject *</label>
                        <input type="text" id="contact-subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="contact-message">Message *</label>
                        <textarea id="contact-message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
                <div id="contact-success" class="success-message" style="display: none;">
                    <p>Thank you for reaching out! We've received your message and will get back to you soon.</p>
                </div>
            </div>
        </section>
    </main>
<?php
$footer_scripts = '<script>
document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById("contact-form");
    var successMsg = document.getElementById("contact-success");
    if (form && successMsg) {
        form.addEventListener("submit", function(e) {
            e.preventDefault();
            var name = document.getElementById("contact-name").value;
            var email = document.getElementById("contact-email").value;
            var subject = document.getElementById("contact-subject").value;
            var message = document.getElementById("contact-message").value;
            if (!name || !email || !subject || !message) { alert("Please fill out all required fields."); return; }
            form.style.display = "none";
            successMsg.style.display = "block";
        });
    }
});
</script>';
require __DIR__ . '/includes/footer.php';
?>
