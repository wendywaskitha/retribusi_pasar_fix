<?php

namespace App\Filament\Widgets;

use App\Models\Pasar;
use App\Models\Pedagang;
use App\Models\RetribusiPembayaran;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AnalyticsOverview extends BaseWidget
{
    
    protected function getStats(): array
    {
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth()->month;

        $currentMonthTotal = RetribusiPembayaran::whereMonth('tanggal_bayar', $currentMonth)
            ->sum('total_biaya');

        $lastMonthTotal = RetribusiPembayaran::whereMonth('tanggal_bayar', $lastMonth)
            ->sum('total_biaya');

        $percentageChange = $lastMonthTotal != 0
            ? (($currentMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100
            : 100;

        return [
            Stat::make('Total Collection This Month', 'Rp ' . number_format($currentMonthTotal, 0, ',', '.'))
                ->description('Compared to last month: ' . number_format($percentageChange, 1) . '%')
                ->descriptionIcon($percentageChange > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($percentageChange > 0 ? 'success' : 'danger'),

            Stat::make('Active Merchants', Pedagang::count())
                ->description('Total registered merchants')
                ->color('success'),

            Stat::make('Markets', Pasar::count())
                ->description('Total active markets')
                ->color('success'),
        ];
    }
}
