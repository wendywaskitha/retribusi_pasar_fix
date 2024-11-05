<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\RetribusiPembayaran;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;

class KolektorCollectionWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pengumpulan Retribusi';

    public ?string $selectedMonth = null;

    protected int | string | array $columnSpan = 'full';

    protected function getFormSchema(): array
    {
        return [
            Select::make('selectedMonth')
                ->label('Pilih Bulan')
                ->options($this->getMonthOptions())
                ->default(now()->format('m'))
                ->reactive()
                ->afterStateUpdated(fn () => $this->updateChartData()),
        ];
    }

    protected function getMonthOptions(): array
    {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->locale('id')->monthName;
            $months[sprintf('%02d', $i)] = $monthName;
        }
        return $months;
    }

    protected function getData(): array
    {
        $user = Auth::user();

        if (!$user->hasRole('kolektor')) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $selectedMonth = $this->selectedMonth ?? now()->format('m');
        $currentYear = now()->year;

        // Get assigned pasar IDs
        $pasarIds = $user->pasars->pluck('id');

        // Get collections for each day of the selected month
        $collections = RetribusiPembayaran::query()
            ->whereIn('pasar_id', $pasarIds)
            ->whereYear('tanggal_bayar', $currentYear)
            ->whereMonth('tanggal_bayar', $selectedMonth)
            ->selectRaw('DATE(tanggal_bayar) as date, SUM(total_biaya) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare data for all days in the month
        $daysInMonth = Carbon::create($currentYear, $selectedMonth)->daysInMonth;
        $dailyData = array_fill(1, $daysInMonth, 0);
        foreach ($collections as $collection) {
            $day = (int)Carbon::parse($collection->date)->format('d');
            $dailyData[$day] = $collection->total;
        }

        // Create labels for all days in the month
        $labels = range(1, $daysInMonth);

        return [
            'datasets' => [
                [
                    'label' => 'Total Retribusi',
                    'data' => array_values($dailyData),
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
