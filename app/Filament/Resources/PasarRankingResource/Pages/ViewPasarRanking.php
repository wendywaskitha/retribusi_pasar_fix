<?php

namespace App\Filament\Resources\PasarRankingResource\Pages;

use Filament\Actions;
use Illuminate\Support\Carbon;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\PasarRankingResource;
use Illuminate\Support\HtmlString;

class ViewPasarRanking extends ViewRecord
{
    protected static string $resource = PasarRankingResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Pasar Details')
                    ->schema([
                        TextEntry::make('name')->label('Pasar Name'),
                        TextEntry::make('address'),
                        TextEntry::make('kecamatan.name')->label('Kecamatan'),
                        TextEntry::make('desa.name')->label('Desa'),
                        TextEntry::make('latitude'),
                        TextEntry::make('longitude'),
                    ])->columns(2),
                // Section::make('Retribusi Information')
                //     ->schema([
                //         TextEntry::make('total_retribusi')
                //             ->label('Total Retribusi (Current Month)')
                //             ->money('IDR'),
                //         // Add more retribusi-related information here
                //     ]),
                Section::make('Retribusi Information')
                    ->schema([
                        TextEntry::make('period')
                            ->label('Period')
                            ->state(function ($record) {
                                $firstTransaction = $record->retribusi_pembayarans()
                                    ->orderBy('tanggal_bayar')
                                    ->first();

                                $lastTransaction = $record->retribusi_pembayarans()
                                    ->orderBy('tanggal_bayar', 'desc')
                                    ->first();

                                if ($firstTransaction && $lastTransaction) {
                                    $start = Carbon::parse($firstTransaction->tanggal_bayar);
                                    $end = Carbon::parse($lastTransaction->tanggal_bayar);

                                    if ($start->format('F Y') === $end->format('F Y')) {
                                        return $start->format('F Y');
                                    }

                                    return $start->format('F Y') . ' - ' . $end->format('F Y');
                                }

                                return 'No transactions';
                            }),
                        TextEntry::make('total_retribusi')
                            ->label(new HtmlString('<strong><h1>Total Retribusi</h1></strong>'))
                            ->state(function ($record) {
                                return $record->retribusi_pembayarans()->sum('total_biaya');
                            })
                            ->money('IDR'),
                        TextEntry::make('transaction_count')
                            ->label('Total Transaksi')
                            ->state(function ($record) {
                                return $record->retribusi_pembayarans()->count();
                            }),
                    ])->columns(2),
            ]);
    }
}
