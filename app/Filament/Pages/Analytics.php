<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LowStockProductsWidget;
use App\Filament\Widgets\SalesChartWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\TopSellingProductsWidget;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;

class Analytics extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected static string|\UnitEnum|null $navigationGroup = 'Reports';

    protected static ?string $navigationLabel = 'Analytics & Reports';

    protected static ?string $title = 'Analytics & Reports';

    protected static ?string $slug = 'analytics';

    protected static ?int $navigationSort = 50;

    protected string $view = 'filament.pages.analytics';

    /**
     * @return array<class-string<Widget>>
     */
    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            TopSellingProductsWidget::class,
            SalesChartWidget::class,
            LowStockProductsWidget::class,
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema(fn (): array => $this->getWidgetsSchemaComponents($this->getWidgets())),
            ]);
    }
}
