<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Puppy;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Demo data for storefront customer accounts: orders (including guest-email match), puppies, reviews.
 * Log in as customer@puppiary.test / password
 */
class CustomerDashboardSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('is_active', true)->get();
        if ($products->count() < 2) {
            $this->command->warn('Run ProductSeeder first (need active products).');

            return;
        }

        $customerAttrs = [
            'name' => 'Demo Customer',
            'password' => Hash::make('password'),
            'phone' => '+2348012345678',
        ];
        if (Schema::hasColumn('users', 'notify_order_updates')) {
            $customerAttrs['notify_order_updates'] = true;
            $customerAttrs['notify_marketing'] = true;
        }
        if (Schema::hasColumn('users', 'shipping_address')) {
            $customerAttrs['shipping_address'] = [
                'line1' => '12 Admiralty Way',
                'line2' => 'Lekki Phase 1',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'postal' => '101245',
                'country' => 'NG',
            ];
        }

        $customer = User::updateOrCreate(
            ['email' => 'customer@puppiary.test'],
            $customerAttrs
        );

        $testPatch = [];
        if (Schema::hasColumn('users', 'shipping_address')) {
            $testPatch['shipping_address'] = [
                'line1' => '45 Test Street',
                'city' => 'Abuja',
                'state' => 'FCT',
                'postal' => '900108',
                'country' => 'NG',
            ];
        }
        if (Schema::hasColumn('users', 'notify_order_updates')) {
            $testPatch['notify_order_updates'] = true;
            $testPatch['notify_marketing'] = false;
        }
        if ($testPatch !== []) {
            User::where('email', 'test@example.com')->update($testPatch);
        }

        $this->seedOrders($customer, $products);
        $this->seedPuppies($customer);
        $this->seedReviews($customer, $products);
    }

    private function seedOrders(User $customer, $products): void
    {
        if (Order::where('user_id', $customer->id)->exists()) {
            return;
        }

        $scenarios = [
            [
                'status' => 'shipped',
                'tracking_number' => 'PUP-DEMO-1001',
                'tracking_url' => 'https://example.com/track/PUP-DEMO-1001',
                'shipped_at' => now()->subDays(3),
            ],
            [
                'status' => 'delivered',
                'tracking_number' => 'PUP-DEMO-0998',
                'tracking_url' => null,
                'shipped_at' => now()->subWeeks(2),
            ],
            [
                'status' => 'paid',
                'tracking_number' => null,
                'tracking_url' => null,
                'shipped_at' => null,
            ],
        ];

        foreach ($scenarios as $meta) {
            $n = min(2, $products->count());
            $picked = $products->random($n);
            $orderProducts = $picked instanceof Product
                ? collect([$picked])
                : $picked->unique('id')->values();
            if ($orderProducts->isEmpty()) {
                $orderProducts = collect([$products->first()]);
            }

            $total = 0;
            $items = [];
            foreach ($orderProducts as $product) {
                $qty = rand(1, 2);
                $price = $product->sale_price ?? $product->price;
                $subtotal = $price * $qty;
                $total += $subtotal;
                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $qty,
                    'price' => $price,
                ];
            }

            $deliveryFee = 4800;
            $orderRow = [
                'user_id' => $customer->id,
                'total_amount' => $total + $deliveryFee,
                'status' => $meta['status'],
                'payment_status' => 'paid',
                'payment_method' => 'paystack',
                'shipping_address' => "{$customer->name}\n12 Admiralty Way\nLekki, Lagos 101245\nNigeria",
                'email' => $customer->email,
                'fullname' => $customer->name,
                'phone' => $customer->phone ?? '+2348012345678',
            ];
            if (Schema::hasColumn('orders', 'tracking_number')) {
                $orderRow['tracking_number'] = $meta['tracking_number'];
                $orderRow['tracking_url'] = $meta['tracking_url'];
                $orderRow['shipped_at'] = $meta['shipped_at'];
            }
            $order = Order::create($orderRow);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }

        // Guest-style order tied to same email (shows up in order history via email match)
        if (! Order::where('email', $customer->email)->whereNull('user_id')->exists()) {
            $p = $products->random();
            $price = $p->sale_price ?? $p->price;
            $qty = 1;
            $deliveryFee = 4800;
            $guestRow = [
                'user_id' => null,
                'total_amount' => $price * $qty + $deliveryFee,
                'status' => 'delivered',
                'payment_status' => 'paid',
                'payment_method' => 'paypal',
                'shipping_address' => "Guest checkout\nSame email as account",
                'email' => $customer->email,
                'fullname' => $customer->name,
                'phone' => $customer->phone,
            ];
            if (Schema::hasColumn('orders', 'tracking_number')) {
                $guestRow['tracking_number'] = 'PUP-GUEST-7700';
                $guestRow['shipped_at'] = now()->subMonth();
            }
            $order = Order::create($guestRow);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $p->id,
                'product_name' => $p->name,
                'quantity' => $qty,
                'price' => $price,
            ]);
        }
    }

    private function seedPuppies(User $customer): void
    {
        $defaults = [
            [
                'name' => 'Koko',
                'breed' => 'Golden Retriever',
                'birth_date' => now()->subWeeks(16),
                'weight' => 8.5,
                'size_category' => 'medium',
                'health_notes' => 'Sensitive tummy — grain-free food only. Vet cleared for treats.',
            ],
            [
                'name' => 'Biscuit',
                'breed' => 'French Bulldog',
                'birth_date' => now()->subWeeks(28),
                'weight' => 6.2,
                'size_category' => 'small',
                'health_notes' => null,
            ],
        ];

        foreach ($defaults as $data) {
            if (! Schema::hasColumn('puppies', 'health_notes')) {
                unset($data['health_notes']);
            }
            Puppy::updateOrCreate(
                [
                    'user_id' => $customer->id,
                    'name' => $data['name'],
                ],
                $data
            );
        }
    }

    private function seedReviews(User $customer, $products): void
    {
        $purchasedIds = OrderItem::query()
            ->whereHas('order', function ($q) use ($customer): void {
                $q->where(function ($q2) use ($customer): void {
                    $q2->where('user_id', $customer->id)
                        ->orWhere('email', $customer->email);
                });
            })
            ->whereNotNull('product_id')
            ->distinct()
            ->pluck('product_id');

        if ($purchasedIds->isEmpty()) {
            return;
        }

        $candidates = Product::whereIn('id', $purchasedIds)->where('is_active', true)->take(4)->get();
        $existing = $customer->reviews()->pluck('product_id');

        $i = 0;
        foreach ($candidates as $product) {
            if ($existing->contains($product->id)) {
                continue;
            }

            Review::firstOrCreate(
                [
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                ],
                [
                    'rating' => $i === 0 ? 5 : 4,
                    'title' => $i === 0 ? 'Our pup loves this' : 'Solid quality',
                    'comment' => $i === 0
                        ? 'Ordered from my dashboard reorder flow — fast delivery and exactly as described.'
                        : 'Happy with this purchase. Would buy again.',
                    'is_approved' => $i === 0,
                    'is_featured' => false,
                    'puppy_age_at_review' => 16,
                    'breed' => 'Golden Retriever',
                    'author_name' => $customer->name,
                    'author_email' => $customer->email,
                ]
            );
            $i++;
            if ($i >= 2) {
                break;
            }
        }
    }
}
