<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\RetribusiPembayaran;
use Filament\Forms\Components\Select;

class KecamatanComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Kecamatan Performance';

    // Make the filter period public and reactive
    public $filterPeriod = 30;

    // Enable polling if you want real-time updates (optional)
    protected static ?string $pollingInterval = null;

    // Enable lazy loading (optional)
    protected static bool $isLazy = true;

    protected function getFormSchema(): array
    {
        return [
            Select::make('filterPeriod')
                ->label('Time Period')
                ->options([
                    7 => 'Last 7 days',
                    30 => 'Last 30 days',
                    60 => 'Last 60 days',
                    90 => 'Last 90 days',
                ])
                ->default(30)
                ->reactive()
                ->afterStateUpdated(function ($state) {
                    $this->filterPeriod = $state;
                    $this->updateChartData();
                }),
        ];
    }

    protected function getData(): array
    {
        $query = RetribusiPembayaran::select(
            'kecamatans.name as kecamatan',
            DB::raw('COUNT(DISTINCT pedagangs.id) as total_merchants'),
            DB::raw('SUM(retribusi_pembayarans.total_biaya) as total_collection')
        )
            ->join('pedagangs', 'retribusi_pembayarans.pedagang_id', '=', 'pedagangs.id')
            ->join('kecamatans', 'pedagangs.kecamatan_id', '=', 'kecamatans.id')
            ->where('retribusi_pembayarans.status', 'lunas')
            ->where('tanggal_bayar', '>=', now()->subDays($this->filterPeriod))
            ->groupBy('kecamatans.id', 'kecamatans.name');

        $data = $query->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Collection',
                    'data' => $data->pluck('total_collection')->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Total Merchants',
                    'data' => $data->pluck('total_merchants')->toArray(),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('kecamatan')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    // Optional: Add chart options
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
