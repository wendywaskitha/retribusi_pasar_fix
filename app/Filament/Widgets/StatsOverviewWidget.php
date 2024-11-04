<?php
namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Pasar;
use App\Models\Pedagang;
use Livewire\Attributes\On;
use App\Models\TargetRetribusi;
use App\Models\RetribusiPembayaran;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverviewWidget extends BaseWidget
{
    // Add this to make the widget real-time
    protected static ?string $pollingInterval = null;

    // Add this to handle date filter
    public ?string $date = null;

    // Optional: Configure the widget
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return true;
    }

    protected function getStats(): array
    {
        // Use current date if no filter is set
        $currentDate = $this->date ? Carbon::parse($this->date) : now();

        // Get yearly target from target_retribusi table
        $yearlyTarget = TargetRetribusi::where('tahun', $currentDate->year)
            ->first()?->target_amount ?? 0;

        // Get total realization for the year
        $yearlyRealization = RetribusiPembayaran::whereYear('tanggal_bayar', $currentDate->year)
            ->where('status', 'lunas')
            ->sum('total_biaya');

        // Calculate percentage of realization
        $percentageRealization = $yearlyTarget > 0
            ? round(($yearlyRealization / $yearlyTarget) * 100, 2)
            : 0;

        // Get total pedagang
        $totalPedagang = Pedagang::count();

        // Get today's collection
        $todayCollection = RetribusiPembayaran::whereDate('tanggal_bayar', $currentDate)
            ->where('status', 'lunas')
            ->sum('total_biaya');

        return [
            Stat::make('Target Retribusi Tahunan', 'Rp ' . number_format($yearlyTarget, 0, ',', '.'))
                ->description('Target tahun ' . $currentDate->year)
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),

            Stat::make('Realisasi Retribusi', 'Rp ' . number_format($yearlyRealization, 0, ',', '.'))
                ->description($percentageRealization . '% dari target')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($percentageRealization >= 100 ? 'success' : 'warning'),

            Stat::make('Pemasukan Hari Ini', 'Rp ' . number_format($todayCollection, 0, ',', '.'))
                ->description('Total pedagang: ' . number_format($totalPedagang))
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success'),
        ];
    }

        /**
         * Handle the Livewire `refresh` event.
         *
         * @return void
         */
    #[On('refresh')]
    public function refresh(): void
    {
        $this->render();
    }
}
