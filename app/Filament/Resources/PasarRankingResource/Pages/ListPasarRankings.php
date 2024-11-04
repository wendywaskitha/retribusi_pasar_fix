<?php

namespace App\Filament\Resources\PasarRankingResource\Pages;

use NumberFormatter;
use App\Models\Pasar;
use Filament\Actions;
use Filament\Actions\Action;
use App\Models\RetribusiPembayaran;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PasarRankingResource;

class ListPasarRankings extends ListRecords
{
    protected static string $resource = PasarRankingResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Action::make('totalRetribusi')
    //             ->label(function () {
    //                 try {
    //                     $total = $this->getTotalRetribusi();
    //                     $formatter = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
    //                     return 'Total Retribusi: ' . $formatter->formatCurrency($total, 'IDR');
    //                 } catch (\Exception $e) {
    //                     // Log the error
    //                     \Log::error('Error calculating total retribusi: ' . $e->getMessage());
    //                     return 'Total Retribusi: N/A';
    //                 }
    //             })
    //             ->color('success')
    //             ->icon('heroicon-o-currency-dollar')
    //             ->extraAttributes(['class' => 'cursor-default'])
    //             ->disabled()
    //     ];
    // }

    // protected function getTotalRetribusi(): float
    // {
    //     // Implement your logic to calculate total retribusi here
    //     // For example:
    //     return Pasar::withTotalRetribusi()
    //         ->sum('total_retribusi');
    // }
}
