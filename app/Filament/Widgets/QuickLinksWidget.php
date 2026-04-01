<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickLinksWidget extends Widget
{
    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.quick-links-widget';

    protected int|string|array $columnSpan = 1;

    public function getLinks(): array
    {
        return [
            ['label' => 'Orders', 'url' => route('filament.admin.resources.orders.index'), 'icon' => 'heroicon-o-shopping-cart'],
            ['label' => 'Products', 'url' => route('filament.admin.resources.products.index'), 'icon' => 'heroicon-o-rectangle-stack'],
            ['label' => 'Customers', 'url' => route('filament.admin.resources.users.index'), 'icon' => 'heroicon-o-users'],
            ['label' => 'Reviews', 'url' => route('filament.admin.resources.reviews.index'), 'icon' => 'heroicon-o-star'],
            ['label' => 'New Product', 'url' => route('filament.admin.resources.products.create'), 'icon' => 'heroicon-o-plus'],
            ['label' => 'New Order', 'url' => route('filament.admin.resources.orders.create'), 'icon' => 'heroicon-o-plus'],
        ];
    }
}
