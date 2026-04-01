<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::where('is_active', true)->get();

        if ($products->isEmpty()) {
            $this->command->warn('Run ProductSeeder first.');

            return;
        }

        $statuses = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];

        for ($i = 0; $i < 20; $i++) {
            $user = $users->isNotEmpty() ? $users->random() : null;
            $itemCount = rand(1, 4);
            $orderProducts = $products->random(min($itemCount, $products->count()));

            $total = 0;
            $items = [];
            foreach ($orderProducts as $product) {
                $qty = rand(1, 3);
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
            $grandTotal = $total + $deliveryFee;

            $order = Order::create([
                'user_id' => $user?->id,
                'total_amount' => $grandTotal,
                'status' => $statuses[array_rand($statuses)],
                'payment_status' => 'paid',
                'payment_method' => ['paystack', 'paypal'][rand(0, 1)],
                'shipping_address' => fake()->streetAddress()."\n".fake()->city().', '.fake()->state(),
                'email' => $user?->email ?? fake()->safeEmail(),
                'fullname' => $user?->name ?? fake()->name(),
                'phone' => $user?->phone ?? fake()->phoneNumber(),
            ]);

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
    }
}
