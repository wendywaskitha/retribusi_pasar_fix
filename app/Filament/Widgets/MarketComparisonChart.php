<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\RetribusiPembayaran;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

class MarketComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Market Comparison';

    public $filterPeriod = 30;
    public $startDate;
    public $endDate;

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

            DatePicker::make('startDate')
                ->label('Start Date')
                ->reactive()
                ->afterStateUpdated(function ($state) {
                    $this->startDate = $state;
                }),

            DatePicker::make('endDate')
                ->label('End Date')
                ->reactive()
                ->afterStateUpdated(function ($state) {
                    $this->endDate = $state;
                }),
        ];
    }

    protected function getData(): array
    {
        $query = RetribusiPembayaran::select(
            'pasars.name as pasar',
            DB::raw('COUNT(*) as total_transactions'),
            DB::raw('SUM(total_biaya) as total_amount')
        )
        ->join('pasars', 'retribusi_pembayarans.pasar_id', '=', 'pasars.id')
        ->where('status', 'lunas')
        ->groupBy('pasars.id', 'pasars.name');

        // Apply date filter
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_bayar', [$this->startDate, $this->endDate]);
        } else {
            $query->where('tanggal_bayar', '>=', Carbon::now()->subDays($this->filterPeriod));
        }

        $data = $query->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Collection',
                    'data' => $data->pluck('total_amount')->toArray(),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Total Transactions',
                    'data' => $data->pluck('total_transactions')->toArray(),
                    'type' => 'line',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'tension' => 0.1,
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
