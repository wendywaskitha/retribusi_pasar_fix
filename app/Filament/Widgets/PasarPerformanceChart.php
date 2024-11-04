<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\RetribusiPembayaran;

class PasarPerformanceChart extends ChartWidget
{
    protected static ?string $heading = 'Pasar Performance';

    protected function getData(): array
    {
        $data = RetribusiPembayaran::select('pasars.name as pasar', DB::raw('SUM(total_biaya) as total'))
            ->join('pasars', 'retribusi_pembayarans.pasar_id', '=', 'pasars.id')
            ->where('status', 'lunas')
            ->groupBy('pasars.id', 'pasars.name')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Collection',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('pasar')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
