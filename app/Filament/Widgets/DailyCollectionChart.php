<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\RetribusiPembayaran;

class DailyCollectionChart extends ChartWidget
{
    protected static ?string $heading = 'Daily Collection';

    protected function getData(): array
    {
        $data = RetribusiPembayaran::select(DB::raw('DATE(tanggal_bayar) as date'), DB::raw('SUM(total_biaya) as total'))
            ->where('status', 'lunas')
            ->where('tanggal_bayar', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Daily Collection',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $data->pluck('date')->map(function ($date) {
                return Carbon::parse($date)->format('M d');
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
