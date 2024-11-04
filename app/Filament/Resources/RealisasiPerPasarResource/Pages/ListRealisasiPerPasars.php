<?php

namespace App\Filament\Resources\RealisasiPerPasarResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Support\Enums\ActionSize;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RealisasiPerPasarResource;

class ListRealisasiPerPasars extends ListRecords
{
    protected static string $resource = RealisasiPerPasarResource::class;

    protected static string $view = 'filament.resources.realisasi-per-pasar-resource.pages.list-realisasi-per-pasars';

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         // Actions\CreateAction::make(),
    //         Action::make('refresh')
    //             ->label('Refresh Data')
    //             ->icon('heroicon-o-arrow-path')
    //             ->size(ActionSize::Small)
    //             ->action(fn () => $this->refresh()),
    //     ];
    // }

    // public function refresh(): void
    // {
    //     $this->resetTable();
    // }
}
