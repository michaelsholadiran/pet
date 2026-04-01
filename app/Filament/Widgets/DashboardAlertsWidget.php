<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Review;
use Filament\Widgets\Widget;

class DashboardAlertsWidget extends Widget
{
    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.dashboard-alerts-widget';

    protected int|string|array $columnSpan = 'full';

    public function getAlerts(): array
    {
        $alerts = [];

        $lowStockCount = Product::where('is_active', true)->where('stock_quantity', '<=', 5)->count();
        if ($lowStockCount > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$lowStockCount} product(s) are low on stock (≤5 units)",
                'url' => route('filament.admin.resources.products.index'),
                'icon' => 'heroicon-o-exclamation-triangle',
            ];
        }

        $pendingReviews = Review::where('is_approved', false)->count();
        if ($pendingReviews > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "{$pendingReviews} review(s) pending approval",
                'url' => route('filament.admin.resources.reviews.index'),
                'icon' => 'heroicon-o-chat-bubble-left-ellipsis',
            ];
        }

        $outOfStock = Product::where('is_active', true)->where('stock_quantity', 0)->count();
        if ($outOfStock > 0) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "{$outOfStock} product(s) are out of stock",
                'url' => route('filament.admin.resources.products.index'),
                'icon' => 'heroicon-o-x-circle',
            ];
        }

        if (empty($alerts)) {
            $alerts[] = [
                'type' => 'success',
                'message' => 'All good! No pending alerts.',
                'url' => null,
                'icon' => 'heroicon-o-check-circle',
            ];
        }

        return $alerts;
    }
}
