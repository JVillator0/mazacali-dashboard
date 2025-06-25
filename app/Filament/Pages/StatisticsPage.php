<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class StatisticsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.statistics-page';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Statistics');
    }

    public function getTitle(): string
    {
        return __('Statistics Dashboard');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatisticsExpensesWidget::class,
            \App\Filament\Widgets\StatisticsSalesWidget::class,
        ];
    }
}
