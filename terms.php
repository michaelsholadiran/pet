<?php
require_once __DIR__ . '/includes/config.php';
$page_title = 'Terms & Conditions - Puppiary';
$page_description = 'Understand the terms that govern your use of Puppiary and our services.';
$page_canonical = '/terms';
$json_ld_scripts = [
    ['@context' => 'https://schema.org', '@type' => 'WebPage', 'name' => $page_title, 'url' => SITE_URL . '/terms', 'description' => $page_description]
];
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/header.php';
?>
    <main>
        <section class="policy-section">
            <h1>Terms & Conditions</h1>
            <p><strong>Last Updated: November 22, 2025</strong></p>
            <article>
                <p>By accessing the Puppiary website ("the Service") or placing an order, you agree to comply with and be bound by these Terms of Service. These terms are designed to ensure clarity and transparency in all your interactions with our brand. If you do not agree to abide by these terms, please do not use this Service.</p>
            </article>
            <article>
                <h2>1. Agreement to Terms and Use License</h2>
                <h3>1.1. Agreement</h3>
                <p>By accessing and using this website, you accept and agree to be bound by the terms and provisions of this agreement.</p>
                <h3>1.2. Use License</h3>
                <p>Permission is granted to temporarily download one copy of the materials (information) on the Puppiary website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title. Under this license, you may not:</p>
                <ul>
                    <li>Modify or copy the materials.</li>
                    <li>Use the materials for any commercial purpose or for any public display.</li>
                    <li>Attempt to decompile or reverse engineer any software contained on the website.</li>
                    <li>Transfer the materials to another person or "mirror" the materials on any other server.</li>
                    <li>Remove any copyright or other proprietary notations from the materials.</li>
                </ul>
            </article>
            <article>
                <h2>2. Ordering and Customer Information</h2>
                <h3>2.1. Accuracy of Information</h3>
                <p>You agree that all information provided during the ordering process (including shipping address, contact details, and payment information) is accurate, current, and complete. Puppiary is not responsible for delays or losses resulting from inaccurate information provided by the customer.</p>
                <h3>2.2. Acceptance of Order</h3>
                <p>Your order constitutes an offer to purchase a product. All orders are subject to acceptance by Puppiary. We reserve the right to refuse or cancel an order for any reason, including product or pricing errors.</p>
                <h3>2.3. Payment</h3>
                <p>All prices are final at the time of order placement. Payment is required in full before dispatch, except where "Payment on Delivery (PoD)" is explicitly offered and selected at checkout.</p>
            </article>
            <article>
                <h2>3. Shipping and Delivery</h2>
                <h3>3.1. Processing</h3>
                <p>Orders are processed within 24 hours as outlined in our Transaction Blueprint.</p>
                <h3>3.2. Delivery Risk</h3>
                <p>Risk of loss and title for items purchased pass to you upon our delivery to the courier service.</p>
                <h3>3.3. Delays</h3>
                <p>While we strive for timely delivery, Puppiary is not responsible for delays caused by third-party courier services, customs procedures, or customer unavailability during the delivery attempt.</p>
                <h3>3.4. Failed Delivery</h3>
                <p>If a shipment is returned to Puppiary due to customer refusal, customer unavailability, or incorrect address, the customer may be responsible for re-shipping fees.</p>
            </article>
            <article>
                <h2>4. Guarantees and Returns</h2>
                <h3>4.1. Integration of Policy</h3>
                <p>All purchases are covered by our comprehensive guarantee policies, which are incorporated into these terms by reference. These include:</p>
                <ul>
                    <li><strong>24-Month Durability Promise:</strong> Covering products that break or fail due to quality issues.</li>
                    <li><strong>30-Day Happiness Guarantee:</strong> Allowing returns for unused, non-defective items within 30 days.</li>
                </ul>
            </article>
            <article>
                <h2>5. Disclaimer and Limitation of Liability</h2>
                <h3>5.1. Disclaimer</h3>
                <p>The materials on Puppiary's website are provided on an 'as is' basis. Puppiary makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability or fitness for a particular purpose.</p>
                <h3>5.2. Limitations</h3>
                <p>In no event shall Puppiary or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on Puppiary's website.</p>
                <h3>5.3. Product Liability</h3>
                <p>Puppiary's liability for any product sold is limited to the replacement of the product or refund of the purchase price. We shall not be liable for any indirect, incidental, or consequential damages related to the purchase or use of our products.</p>
            </article>
            <article>
                <h2>6. General Provisions</h2>
                <h3>6.1. Accuracy of Materials</h3>
                <p>The materials appearing on Puppiary's website could include technical, typographical, or photographic errors. Puppiary does not warrant that any of the materials on its website are accurate, complete, or current.</p>
                <h3>6.2. Links</h3>
                <p>Puppiary has not reviewed all of the sites linked to its website and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by Puppiary of the site. Use of any such linked website is at the user's own risk.</p>
                <h3>6.3. Modifications</h3>
                <p>Puppiary may revise these Terms of Service for its website at any time without notice. By using this website, you are agreeing to be bound by the then-current version of these terms of service.</p>
                <h3>6.4. Governing Law</h3>
                <p>These terms and conditions are governed by and construed in accordance with the laws of the jurisdiction in which Puppiary operates, and you irrevocably submit to the exclusive jurisdiction of the courts in that location.</p>
            </article>
        </section>
    </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
