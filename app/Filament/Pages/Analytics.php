<?php

namespace App\Filament\Pages;

use App\Models\Pasar;
use App\Models\Pedagang;
use Filament\Pages\Page;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use App\Models\RetribusiPembayaran;
use Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\AnalyticsOverview;
use App\Filament\Widgets\TimeComparisonChart;
use App\Filament\Widgets\DailyCollectionChart;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Widgets\MarketComparisonChart;
use App\Filament\Widgets\MonthlyRetribusiChart;
use App\Filament\Widgets\PasarPerformanceChart;
use App\Filament\Widgets\KecamatanComparisonChart;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Analytics extends Page
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Analytics';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $title = 'Analytics & Reports';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.analytics';

    public function getHeaderWidgets(): array
    {
        return [
            AnalyticsOverview::class,
        ];
    }

    public function getFooterWidgets(): array
    {
        return [
            MonthlyRetribusiChart::class,
            PasarPerformanceChart::class,
            DailyCollectionChart::class,
            MarketComparisonChart::class,
            KecamatanComparisonChart::class,
            TimeComparisonChart::class,
        ];
    }
}
