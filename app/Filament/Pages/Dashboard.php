<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Filament\Widgets\UserInfoWidget;
use Filament\Forms\Components\DatePicker;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\TargetRealisasiChart;
use App\Filament\Widgets\PedagangPerPasarChart;
use App\Filament\Widgets\RetribusiPerPasarChart;
use App\Filament\Widgets\KolektorCollectionWidget;
use App\Filament\Widgets\KolektorDailyStatsWidget;

class Dashboard extends \Filament\Pages\Dashboard
{
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'date' => now()->format('Y-m-d'),
        ]);
    }

    // public function form(Form $form): Form
    // {

    //     return $form
    //         ->schema([
    //             DatePicker::make('date')
    //                 ->label('Filter Tanggal')
    //                 ->default(now())
    //                 ->native(false)
    //                 ->live()
    //                 ->afterStateUpdated(function () {
    //                     $this->refreshDashboard();
    //                 }),
    //         ])
    //         ->statePath('data');
    // }

    public function getHeaderWidgets(): array
    {
        return [
            UserInfoWidget::class,
        ];
    }


    public function getWidgets(): array
    {
        $date = $this->data['date'] ?? now()->format('Y-m-d');
        $user = Auth::user();

        if ($user->hasRole('kolektor')) {
            return $this->getKolektorWidgets($date);
        }

        return [
            StatsOverviewWidget::make([
                'date' => $date,
            ]),
            PedagangPerPasarChart::make([
                'date' => $date,
            ]),
            RetribusiPerPasarChart::make([
                'date' => $date,
            ]),
            // TargetRealisasiChart::make([
            //     'date' => $date,
            // ]),
        ];
    }

    protected function getKolektorWidgets(string $date): array
    {
        return [
            KolektorDailyStatsWidget::make([
                'date' => $date,
            ]),
            KolektorCollectionWidget::make([
                'date' => $date,
            ]),
        ];
    }

    protected function refreshDashboard(): void
    {
        $this->dispatch('refresh');
    }

    public function getColumns(): int | array
    {
        return 2;
    }

    public function getTitle(): string
    {
        return 'Dashboard';
    }

    public function getView(): string
    {
        return 'filament.pages.dashboard';
    }

}
