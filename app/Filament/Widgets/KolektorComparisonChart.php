<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\RetribusiPembayaran;

class KolektorComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Perbandingan Antar Kolektor';

    protected function getData(): array
    {
        $kolektors = User::role('kolektor')->get();

        $datasets = [];
        $labels = [];

        // Get last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');
        }

        foreach ($kolektors as $kolektor) {
            $data = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);

                $amount = RetribusiPembayaran::where('user_id', $kolektor->id)
                    ->whereDate('tanggal_bayar', $date)
                    ->sum('total_biaya');

                $data[] = $amount;
            }

            $datasets[] = [
                'label' => $kolektor->name,
                'data' => $data,
                'borderColor' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                'tension' => 0.3,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
