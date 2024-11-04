<?php

namespace App\Filament\Widgets;

use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use App\Models\RetribusiPembayaran;

class MonthlyRetribusiChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Retribusi Collection';

    protected function getData(): array
    {
        $data = Trend::model(RetribusiPembayaran::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('total_biaya');

        return [
            'datasets' => [
                [
                    'label' => 'Total Collection',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
