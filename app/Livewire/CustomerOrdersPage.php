<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerOrdersPage extends Component
{
    use WithPagination;

    #[Layout('layouts.dashboard')]
    public function render()
    {
        view()->share('dashboardPageTitle', 'My orders');

        $orders = auth()->user()
            ->orderHistoryQuery()
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('livewire.customer-orders-page', [
            'orders' => $orders,
        ]);
    }
}
