<?php
$page_title = 'Contact Us - Puppiary';
$page_description = "Questions or feedback? Get in touch with the Puppiary team — we're here to help.";
$page_canonical = '/contact';
$current_nav = 'contact';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="contact-section">
            <h1>Get in Touch</h1>
            <p class="contact-intro">We'd love to hear from you! Whether you have a question, feedback, or just want to say hello, feel free to reach out.</p>
            <div class="tabs" id="contact-tabs">
                <div class="tab-list" role="tablist" aria-label="Contact options">
                    <button class="tab-button active" role="tab" aria-selected="true" aria-controls="tab-phone" id="tab-btn-phone">Phone / WhatsApp</button>
                    <button class="tab-button" role="tab" aria-selected="false" aria-controls="tab-email" id="tab-btn-email">Email</button>
                </div>
                <div class="tab-panels">
                    <div class="tab-panel" id="tab-phone" role="tabpanel" aria-labelledby="tab-btn-phone">
                        <div class="contact-card">
                            <h2>Call or WhatsApp us</h2>
                            <p>Our friendly team is here to help. Reach us on WhatsApp or give us a call — we typically respond within minutes during business hours.</p>
                            <ul class="phone-list">
                                <li>
                                    <span class="carrier">Airtel</span>
                                    <span class="phone-num">0701 642 6458</span>
                                    <div class="call-actions">
                                        <a href="https://wa.me/2347016426458" class="btn btn-small btn-wa" target="_blank" rel="noopener">WhatsApp</a>
                                        <a href="tel:+2347016426458" class="btn btn-small btn-call">Call</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-panel" id="tab-email" role="tabpanel" aria-labelledby="tab-btn-email" hidden>
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
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php
$footer_scripts = '<script>
document.addEventListener("DOMContentLoaded", function() {
    var tabButtons = document.querySelectorAll(".tab-button");
    var tabPanels = document.querySelectorAll(".tab-panel");
    tabButtons.forEach(function(btn) {
        btn.addEventListener("click", function() {
            var target = btn.getAttribute("aria-controls");
            tabButtons.forEach(function(b) { b.classList.remove("active"); b.setAttribute("aria-selected", "false"); });
            tabPanels.forEach(function(p) { p.hidden = true; });
            btn.classList.add("active");
            btn.setAttribute("aria-selected", "true");
            document.getElementById(target).hidden = false;
        });
    });
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
