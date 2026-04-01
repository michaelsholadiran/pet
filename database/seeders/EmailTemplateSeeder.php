<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'key' => 'abandoned_cart',
                'name' => 'Abandoned Cart',
                'subject' => 'You left something behind',
                'body_html' => '<p>Hi {{customer_name}},</p><p>You left some items in your cart. Complete your order and get them delivered to your pup!</p><p><a href="{{cart_url}}">View your cart</a></p><p>– The Puppiary Team</p>',
                'variables' => ['customer_name', 'cart_url', 'items_summary'],
            ],
            [
                'key' => 'welcome',
                'name' => 'Welcome Email',
                'subject' => 'Welcome to Puppiary!',
                'body_html' => '<p>Hi {{customer_name}},</p><p>Thanks for joining Puppiary. We\'re here to help you give your puppy the best start in life.</p><p>Explore our <a href="{{products_url}}">shop</a> or check out our <a href="{{faq_url}}">FAQ</a>.</p><p>– The Puppiary Team</p>',
                'variables' => ['customer_name', 'products_url', 'faq_url'],
            ],
            [
                'key' => 'order_update',
                'name' => 'Order Update',
                'subject' => 'Order {{order_number}} – {{status}}',
                'body_html' => '<p>Hi {{customer_name}},</p><p>Your order {{order_number}} has been updated.</p><p><strong>Status:</strong> {{status}}</p>@if(tracking_url)<p><a href="{{tracking_url}}">Track your shipment</a></p>@endif<p>– The Puppiary Team</p>',
                'variables' => ['customer_name', 'order_number', 'status', 'tracking_url'],
            ],
            [
                'key' => 'post_purchase_review',
                'name' => 'Post-Purchase Review Request',
                'subject' => 'How did {{product_name}} work for your pup?',
                'body_html' => '<p>Hi {{customer_name}},</p><p>Your pup has had some time with {{product_name}}. We\'d love to hear your feedback!</p><p><a href="{{review_url}}">Leave a review</a></p><p>– The Puppiary Team</p>',
                'variables' => ['customer_name', 'product_name', 'review_url'],
            ],
            [
                'key' => 'subscription_reminder',
                'name' => 'Subscription Reminder',
                'subject' => 'Time to restock?',
                'body_html' => '<p>Hi {{customer_name}},</p><p>Based on your last order, you might be running low on supplies. Reorder now and never run out.</p><p><a href="{{products_url}}">Shop now</a></p><p>– The Puppiary Team</p>',
                'variables' => ['customer_name', 'products_url'],
            ],
        ];

        foreach ($templates as $data) {
            EmailTemplate::firstOrCreate(
                ['key' => $data['key']],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
