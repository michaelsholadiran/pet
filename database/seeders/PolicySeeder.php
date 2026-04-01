<?php

namespace Database\Seeders;

use App\Models\Policy;
use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
    public function run(): void
    {
        $policies = [
            [
                'type' => Policy::TYPE_PRIVACY,
                'title' => 'Puppiary Privacy Policy',
                'content' => '<p><strong>Effective Date: November 22, 2025</strong></p>
<p>Your trust is essential to the Puppiary mission. This policy explains exactly how Puppiary collects, uses, and protects your personal information when you use our website and services.</p>
<h2>1. Our Ironclad Promise</h2>
<p>We operate under two non-negotiable principles:</p>
<ul>
<li>We <strong>never</strong> share, sell, rent, or trade your personal data with any third-party marketing companies.</li>
<li>All Personal Data collected is used strictly for order processing, essential communication, and improving your shopping experience.</li>
</ul>
<h2>2. Information Collection and Use</h2>
<p>We collect several different types of information for various purposes to provide and improve our Service to you.</p>
<h2>3. How We Use Your Information</h2>
<p>All Personal Data is used strictly for: Order Fulfillment, Essential Communication, Customer Support, and Improving Our Service.</p>
<h2>4. Security of Data and Storage</h2>
<p>We use industry-standard security measures, including SSL encryption, to protect your Personal Data during transmission and storage.</p>
<h2>5. Contact Us</h2>
<p>If you have any questions about this Privacy Policy, please contact us at <a href="mailto:hello@puppiary.com">hello@puppiary.com</a></p>',
            ],
            [
                'type' => Policy::TYPE_RETURN,
                'title' => 'Puppiary Guarantee & Return Policy',
                'content' => '<p>Our goal is simple: We want you and your pup to be completely happy. We stand by the quality of our gear with two clear promises.</p>
<h2>1. 24-Month Durability Promise</h2>
<p>If your Puppiary product breaks, tears, or fails because of a problem with our quality within 24 months of delivery, we will ensure you get a working item—full cash refund or new replacement. We cover all shipping costs for quality-related returns.</p>
<h2>2. 30-Day Happiness Guarantee</h2>
<p>If you or your puppy simply change your mind, you have 30 days from delivery to return it for a full refund. Item must be unused, unwashed, and in original condition and packaging.</p>
<h2>How to Initiate a Return</h2>
<ol>
<li><strong>Contact Us:</strong> Email <a href="mailto:hello@puppiary.com">hello@puppiary.com</a></li>
<li><strong>Provide Details:</strong> Include your order number and reason for return</li>
<li><strong>Receive Instructions:</strong> We\'ll provide return authorization and shipping instructions</li>
</ol>',
            ],
            [
                'type' => Policy::TYPE_SHIPPING,
                'title' => 'Shipping & Delivery Policy',
                'content' => '<p><strong>Last Updated: November 22, 2025</strong></p>
<p>We deliver nationwide across Nigeria. Here’s what you need to know about shipping your Puppiary order.</p>
<h2>1. Processing Time</h2>
<p>Orders are processed within 24 hours of placement. You will receive a confirmation email once your order ships.</p>
<h2>2. Delivery Time</h2>
<p>Standard delivery is 1–4 working days to major metropolitan areas including Lagos, Abuja, and Port Harcourt. Other regions may take 5–7 working days.</p>
<h2>3. Cash on Delivery</h2>
<p>We offer Cash on Delivery (PoD) in many major cities. Select this option at checkout if available for your area.</p>
<h2>4. Tracking</h2>
<p>Once shipped, you will receive a tracking number to monitor your delivery.</p>
<h2>5. Contact Us</h2>
<p>For shipping questions, email <a href="mailto:hello@puppiary.com">hello@puppiary.com</a></p>',
            ],
        ];

        foreach ($policies as $data) {
            Policy::updateOrCreate(
                ['type' => $data['type']],
                [
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'is_active' => true,
                ]
            );
        }
    }
}
