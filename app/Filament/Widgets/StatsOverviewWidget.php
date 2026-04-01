<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $weekStart = now()->startOfWeek();

        $dailyRevenue = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $today)
            ->sum('total_amount');

        $weeklyRevenue = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $weekStart)
            ->sum('total_amount');

        $dailyOrders = Order::where('created_at', '>=', $today)->count();
        $weeklyOrders = Order::where('created_at', '>=', $weekStart)->count();

        $newCustomersThisWeek = User::where('created_at', '>=', $weekStart)->count();

        $totalCustomers = User::count();
        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $conversionRate = $totalCustomers > 0
            ? round(($totalOrders / $totalCustomers) * 100, 1)
            : 0;

        return [
            Stat::make('Daily Revenue', '₦'.number_format($dailyRevenue / 100, 2))
                ->description('Today\'s sales')
                ->descriptionIcon(Heroicon::OutlinedCurrencyDollar)
                ->url(route('filament.admin.resources.orders.index'))
                ->chart($this->getRevenueChart(7)),

            Stat::make('Weekly Revenue', '₦'.number_format($weeklyRevenue / 100, 2))
                ->description('This week')
                ->descriptionIcon(Heroicon::OutlinedChartBar),

            Stat::make('Orders', $weeklyOrders)
                ->description($dailyOrders.' today')
                ->descriptionIcon(Heroicon::OutlinedShoppingCart)
                ->url(route('filament.admin.resources.orders.index')),

            Stat::make('New Customers', $newCustomersThisWeek)
                ->description('This week')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->url(route('filament.admin.resources.users.index')),

            Stat::make('Conversion Rate', $conversionRate.'%')
                ->description('Orders per customer')
                ->descriptionIcon(Heroicon::OutlinedArrowTrendingUp),
        ];
    }

    protected function getRevenueChart(int $days): array
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $data[] = Order::where('status', '!=', 'cancelled')
                ->whereDate('created_at', $date)
                ->sum('total_amount') / 1000; // Scale for chart
        }

        return $data;
    }
}
