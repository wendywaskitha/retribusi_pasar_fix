<?php

namespace App\Filament\Widgets;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\RetribusiPembayaran;

class RetribusiPerPasarChart extends ChartWidget
{
    protected static ?string $heading = 'Retribusi per Pasar';
    protected static ?int $sort = 3;

    // Add this property to handle the filter
    public ?string $filter = null;

    protected function getData(): array
    {
        $date = $this->filter ? Carbon::parse($this->filter) : now();

        $data = RetribusiPembayaran::select(
            'pasars.name',
            DB::raw('SUM(total_biaya) as total_retribusi')
        )
            ->join('pasars', 'retribusi_pembayarans.pasar_id', '=', 'pasars.id')
            ->whereYear('tanggal_bayar', $date->year)
            ->whereMonth('tanggal_bayar', $date->month)
            ->where('status', 'lunas')
            ->groupBy('pasars.id', 'pasars.name')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Retribusi',
                    'data' => $data->pluck('total_retribusi')->toArray(),
                    'backgroundColor' => [
                        '#10B981', // green
                        '#3B82F6', // blue
                        '#F59E0B', // yellow
                        '#EF4444', // red
                        '#8B5CF6', // purple
                        '#EC4899', // pink
                        '#14B8A6', // teal
                        '#6366F1', // indigo
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            return "Rp " + new Intl.NumberFormat("id-ID").format(context.raw);
                        }',
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) {
                            return "Rp " + new Intl.NumberFormat("id-ID").format(value);
                        }',
                    ],
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
