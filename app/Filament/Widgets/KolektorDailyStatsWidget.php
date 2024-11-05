<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use App\Models\RetribusiPembayaran;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class KolektorDailyStatsWidget extends BaseWidget
{
    // Property to store the date
    public string $date;
    protected function getStats(): array
    {
        $user = Auth::user();

        if (!$user->hasRole('kolektor')) {
            return [];
        }

        $targetDate = Carbon::parse($this->date);

        // Query to get total collection for the day from assigned pasars
        $dailyCollection = RetribusiPembayaran::query()
            ->whereIn('pasar_id', $user->pasars->pluck('id'))
            ->whereDate('tanggal_bayar', $targetDate)
            ->sum('total_biaya');

        // Count distinct pedagang who paid on that date from assigned pasars
        $pedagangPaidCount = RetribusiPembayaran::query()
            ->whereIn('pasar_id', $user->pasars->pluck('id'))
            ->whereDate('tanggal_bayar', $targetDate)
            ->distinct('pedagang_id')
            ->count();

        // Get total pedagang from assigned pasars
        $totalPedagang = $user->assignedPedagang()->count();

        return [
            Stat::make('Total Retribusi Hari Ini', 'Rp ' . number_format($dailyCollection, 0, ',', '.'))
                ->description('Total pembayaran yang terkumpul')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->extraAttributes([
                    'class' => 'stat-card stat-card-collection',
                ]),

            Stat::make('Pedagang Membayar', $pedagangPaidCount)
                ->description('dari total ' . $totalPedagang . ' pedagang')
                ->icon('heroicon-o-users')
                ->color('info')
                ->extraAttributes([
                    'class' => 'stat-card stat-card-paid',
                ]),

            Stat::make('Sisa Pedagang', $totalPedagang - $pedagangPaidCount)
                ->description('Pedagang yang belum membayar')
                ->icon('heroicon-o-user-minus')
                ->color('danger')
                ->extraAttributes([
                    'class' => 'stat-card stat-card-unpaid',
                ]),
        ];
    }
}
