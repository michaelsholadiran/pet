<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CustomerOrderShowPage extends Component
{
    public Order $order;

    #[Layout('layouts.dashboard')]
    public function mount(Order $order): void
    {
        $user = auth()->user();
        $allowed = (int) $order->user_id === (int) $user->id
            || (filled($user->email) && strcasecmp((string) $order->email, (string) $user->email) === 0);

        abort_unless($allowed, 403);

        $this->order = $order->load(['items.product']);
    }

    public function render()
    {
        view()->share('dashboardPageTitle', 'Order #'.$this->order->id);

        $reorderLines = [];
        foreach ($this->order->items as $item) {
            $product = $item->product;
            if ($product && $product->is_active && (int) $product->stock_quantity > 0) {
                $qty = min((int) $item->quantity, max(1, (int) $product->stock_quantity));
                $reorderLines[] = [
                    'quantity' => $qty,
                    'product' => $product->toCatalogArray(),
                ];
            }
        }

        return view('livewire.customer-order-show-page', [
            'reorderLines' => $reorderLines,
            'reorderSkipped' => $this->order->items->count() - count($reorderLines),
        ]);
    }
}
