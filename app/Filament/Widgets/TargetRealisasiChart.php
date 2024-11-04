<?php

namespace App\Filament\Widgets;

use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Models\TargetRetribusi;
use Filament\Widgets\ChartWidget;
use App\Models\RetribusiPembayaran;

class TargetRealisasiChart extends ChartWidget
{
    protected static ?string $heading = 'Target vs Realisasi Retribusi';
    protected static ?int $sort = 4;
    // If you want to make it real-time, specify the polling interval

    // protected static ?string $pollingInterval = '10s';

    // Add this property to handle the filter
    public ?string $filter = null;
    protected function getData(): array
    {
        $date = $this->filter ? Carbon::parse($this->filter) : now();

        // Get target for the year
        $targetRetribusi = TargetRetribusi::where('tahun', $date->year)
            ->first()?->target_amount ?? 0;

        // Calculate monthly target
        $monthlyTarget = $targetRetribusi / 12;

        // Get realization data for each month
        $realisasiData = RetribusiPembayaran::selectRaw('MONTH(tanggal_bayar) as month, SUM(total_biaya) as total')
            ->whereYear('tanggal_bayar', $date->year)
            ->where('status', 'lunas')
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Prepare data for all months
        $labels = [];
        $targetData = [];
        $realisasiData = [];

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::create()->month($month)->format('F');
            $targetData[] = $monthlyTarget;
            $realisasiData[] = $realisasiData[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Target',
                    'data' => $targetData,
                    'borderColor' => '#EF4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                ],

                [
                    'label' => 'Realisasi',
                    'data' => $realisasiData,
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],

            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => "function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }",
                    ],
                ],
            ],

            'elements' => [
                'line' => [
                    'tension' => 0.3,
                ],
            ],
        ];
    }

    #[On('refresh')]
    public function refresh(): void
    {
        $this->render();
    }
}
