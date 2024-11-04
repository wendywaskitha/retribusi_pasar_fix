<?php

namespace App\Filament\Widgets;

use Livewire\Component;
use App\Models\Pedagang;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PedagangPerPasarChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Pedagang per Pasar';
    protected static ?int $sort = 2;

    // Add this property to make the widget poll for updates
    public ?string $filter = null;

    public function getFilteredDate(): Carbon
    {
        return $this->filter ? Carbon::parse($this->filter) : now();
    }

    protected function getData(): array
    {
        $selectedDate = $this->getFilteredDate();

        $data = Pedagang::select('pasars.name', DB::raw('COUNT(*) as total'))
            ->join('pasars', 'pedagangs.pasar_id', '=', 'pasars.id')
            ->whereDate('pedagangs.created_at', '<=', $selectedDate)
            ->groupBy('pasars.id', 'pasars.name')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pedagang',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#10B981', '#3B82F6', '#F59E0B', '#EF4444',
                        '#8B5CF6', '#EC4899', '#14B8A6', '#6366F1',
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    #[On('refresh')]
    public function refresh(): void
    {
        $this->render();
    }
}
