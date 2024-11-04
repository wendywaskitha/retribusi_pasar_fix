<?php

namespace App\Filament\Resources\RetribusiPembayaranResource\Pages;

use Filament\Actions;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Widgets\RetribusiMonthlyChart;
use App\Filament\Widgets\RetribusiStatsOverview;
use App\Filament\Widgets\KolektorComparisonChart;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\RetribusiPembayaranResource;

class ListRetribusiPembayarans extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = RetribusiPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        $widgets = [
            RetribusiStatsOverview::class,
        ];

        // Check if the current user has the 'super_admin' role
        if (Filament::auth()->user()->hasRole('super_admin')) {
            $widgets[] = RetribusiMonthlyChart::class;
            $widgets[] = KolektorComparisonChart::class;
        }

        return $widgets;
    }

    // protected function getFooterWidgets(): array
    // {
    //     return [
    //         RetribusiMonthlyChart::class,
    //         KolektorComparisonChart::class,
    //     ];
    // }

}
