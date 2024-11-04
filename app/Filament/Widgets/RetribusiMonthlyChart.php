<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\RetribusiPembayaran;
use Illuminate\Support\Facades\Auth;

class RetribusiMonthlyChart extends ChartWidget
{
    protected static ?string $heading = 'Perbandingan Retribusi Bulanan';

    protected function getData(): array
    {
        $user = Auth::user();
        $query = RetribusiPembayaran::query();

        if ($user?->hasRole('kolektor')) {
            $query->where('user_id', $user->id);
        }

        $data = [];
        $labels = [];

        // Get last 6 months of data
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            $amount = (clone $query)
                ->whereMonth('tanggal_bayar', $month->month)
                ->whereYear('tanggal_bayar', $month->year)
                ->sum('total_biaya');

            $data[] = $amount;
            $labels[] = $month->format('M Y');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Retribusi',
                    'data' => $data,
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
