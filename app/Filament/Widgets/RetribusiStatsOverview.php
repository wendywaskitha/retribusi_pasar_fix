<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use App\Models\RetribusiPembayaran;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class RetribusiStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected function getStats(): array
    {
        $user = Auth::user();
        $query = RetribusiPembayaran::query();

        // If user is kolektor, only show their collections
        if ($user?->hasRole('kolektor')) {
            $query->where('user_id', $user->id);
        }

        // Today's collection
        $todayCollection = (clone $query)
            ->whereDate('tanggal_bayar', Carbon::today())
            ->sum('total_biaya');

        // Yesterday's collection
        $yesterdayCollection = (clone $query)
            ->whereDate('tanggal_bayar', Carbon::yesterday())
            ->sum('total_biaya');

        // This month's collection
        $thisMonthCollection = (clone $query)
            ->whereMonth('tanggal_bayar', Carbon::now()->month)
            ->whereYear('tanggal_bayar', Carbon::now()->year)
            ->sum('total_biaya');

        // Last month's collection
        $lastMonthCollection = (clone $query)
            ->whereMonth('tanggal_bayar', Carbon::now()->subMonth()->month)
            ->whereYear('tanggal_bayar', Carbon::now()->subMonth()->year)
            ->sum('total_biaya');

        // Calculate percentages for comparison
        $dailyChange = $yesterdayCollection != 0
            ? (($todayCollection - $yesterdayCollection) / $yesterdayCollection) * 100
            : 0;

        $monthlyChange = $lastMonthCollection != 0
            ? (($thisMonthCollection - $lastMonthCollection) / $lastMonthCollection) * 100
            : 0;

        return [
            Stat::make('Hari Ini', 'Rp ' . number_format($todayCollection, 0, ',', '.'))
                ->description($dailyChange >= 0 ? 'Naik ' . number_format(abs($dailyChange), 1) . '%' : 'Turun ' . number_format(abs($dailyChange), 1) . '%')
                ->descriptionIcon($dailyChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($dailyChange >= 0 ? 'success' : 'danger')
                ->chart([
                    $yesterdayCollection,
                    $todayCollection,
                ])
                ->extraAttributes([
                    'style' => 'background-color: rgb(239 246 255) !important;', // bg-blue-50
                ]),

            Stat::make('Bulan Ini', 'Rp ' . number_format($thisMonthCollection, 0, ',', '.'))
                ->description($monthlyChange >= 0 ? 'Naik ' . number_format(abs($monthlyChange), 1) . '%' : 'Turun ' . number_format(abs($monthlyChange), 1) . '%')
                ->descriptionIcon($monthlyChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($monthlyChange >= 0 ? 'success' : 'danger')
                ->chart([
                    $lastMonthCollection,
                    $thisMonthCollection,
                ])
                ->extraAttributes([
                    'style' => 'background-color: rgb(240 253 244);', // bg-green-50
                ]),

            Stat::make('Kemarin', 'Rp ' . number_format($yesterdayCollection, 0, ',', '.'))
                ->chart([
                    $yesterdayCollection,
                ])
                ->color('warning')
                ->extraAttributes([
                    'style' => 'background-color: rgb(250 245 255);', // bg-purple-50
                ]),
        ];
    }
}
