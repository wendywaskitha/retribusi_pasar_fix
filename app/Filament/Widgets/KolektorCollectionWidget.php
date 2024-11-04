<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\RetribusiPembayaran;
use Illuminate\Support\Facades\Auth;

class KolektorCollectionWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pengumpulan Retribusi';

    public string $date;
    

    protected function getData(): array
    {
        $user = Auth::user();
        
        if (!$user->hasRole('kolektor')) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $targetDate = Carbon::parse($this->date);
        
        // Get assigned pasar IDs
        $pasarIds = $user->pasars->pluck('id');

        // Get collections for each hour of the day
        $collections = RetribusiPembayaran::query()
            ->whereIn('pasar_id', $pasarIds)
            ->whereDate('tanggal_bayar', $targetDate)
            ->selectRaw('HOUR(tanggal_bayar) as hour, SUM(total_biaya) as total')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Prepare data for all 24 hours
        $hourlyData = array_fill(0, 24, 0);
        foreach ($collections as $collection) {
            $hourlyData[$collection->hour] = $collection->total;
        }

        // Create labels for all 24 hours
        $labels = array_map(function ($hour) {
            return sprintf('%02d:00', $hour);
        }, range(0, 23));

        return [
            'datasets' => [
                [
                    'label' => 'Total Retribusi',
                    'data' => array_values($hourlyData),
                    'borderColor' => '#36A2EB',
                    'fill' => false,
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
