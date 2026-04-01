<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class CustomerDashboard extends Component
{
    #[Layout('layouts.dashboard')]
    public function render()
    {
        view()->share('dashboardPageTitle', 'Overview');

        $user = auth()->user();
        $ordersQuery = $user->orderHistoryQuery();

        return view('livewire.customer-dashboard', [
            'recentOrders' => (clone $ordersQuery)->with('items')->latest()->take(5)->get(),
            'ordersCount' => (clone $ordersQuery)->count(),
            'puppiesCount' => $user->puppies()->count(),
            'reviewsCount' => $user->reviews()->count(),
        ]);
    }
}
