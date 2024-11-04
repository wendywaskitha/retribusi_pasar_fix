<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\RetribusiPembayaran;
use Filament\Forms\Components\Select;

class TimeComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Time Period Comparison';

    public $filterPeriod = 30;

    protected function getFormSchema(): array
    {
        return [
            Select::make('filterPeriod')
                ->label('Time Period')
                ->options([
                    7 => '7 days',
                    30 => '30 days',
                    60 => '60 days',
                    90 => '90 days',
                ])
                ->default(30)
                ->reactive()
                ->afterStateUpdated(function ($state) {
                    $this->filterPeriod = $state;
                }),
        ];
    }

    protected function getData(): array
    {
        // Current month data
        $currentMonth = RetribusiPembayaran::select(
            DB::raw('DATE(tanggal_bayar) as date'),
            DB::raw('SUM(total_biaya) as total')
        )
        ->where('status', 'lunas')
        ->whereMonth('tanggal_bayar', Carbon::now()->month)
        ->where('tanggal_bayar', '>=', now()->subDays($this->filterPeriod))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Previous month data
        $previousMonth = RetribusiPembayaran::select(
            DB::raw('DATE(tanggal_bayar) as date'),
            DB::raw('SUM(total_biaya) as total')
        )
        ->where('status', 'lunas')
        ->whereMonth('tanggal_bayar', Carbon::now()->subMonth()->month)
        ->where('tanggal_bayar', '>=', now()->subDays($this->filterPeriod))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Current Month',
                    'data' => $currentMonth->pluck('total')->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Previous Month',
                    'data' => $previousMonth->pluck('total')->toArray(),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $currentMonth->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
