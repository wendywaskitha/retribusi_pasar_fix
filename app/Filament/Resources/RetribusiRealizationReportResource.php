<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pasar;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use App\Models\RetribusiPembayaran;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\RetribusiRealizationReport;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RetribusiRealizationReportResource\Pages;
use App\Filament\Resources\RetribusiRealizationReportResource\RelationManagers;

class RetribusiRealizationReportResource extends Resource
{
    protected static ?string $model = RetribusiPembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $modelLabel = 'Realisasi Retribusi';

    protected static ?string $navigationGroup = 'Laporan';

    public static function table(Table $table): Table
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $assignedPasarIds = $user && $user->hasRole('kolektor') ? $user->pasars->pluck('id') : null;

        return $table
            ->modifyQueryUsing(function (Builder $query) use ($assignedPasarIds) {
                if ($assignedPasarIds) {
                    $query->whereHas('pedagang', function ($q) use ($assignedPasarIds) {
                        $q->whereIn('pasar_id', $assignedPasarIds);
                    });
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('pedagang.name')
                    ->label('Pedagang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pedagang.pasar.name')
                    ->label('Pasar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->date()
                    ->label('Tanggal Pembayaran')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_biaya')
                    ->money('idr')
                    ->label('Jumlah Pembayaran')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('pasar_id')
                    ->label('Pasar')
                    ->options(function () use ($assignedPasarIds) {
                        if ($assignedPasarIds) {
                            return Pasar::whereIn('id', $assignedPasarIds)->pluck('name', 'id');
                        }
                        return Pasar::pluck('name', 'id');
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $pasarId): Builder => $query->whereHas('pedagang', fn ($q) => $q->where('pasar_id', $pasarId))
                        );
                    }),
                Filter::make('tanggal_bayar')
                    ->form([
                        DatePicker::make('month')
                            ->label('Bulan dan Tahun')
                            ->displayFormat('F Y')
                            ->format('Y-m'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['month'],
                                fn (Builder $query, $date): Builder => $query->whereYear('tanggal_bayar', substr($date, 0, 4))
                                    ->whereMonth('tanggal_bayar', substr($date, 5, 2))
                            );
                    })
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-arrow-down-circle')
                    ->action(fn (RetribusiPembayaran $record) => static::exportPdf($record))
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_pdf_bulk')
                        ->label('Export Selected to PDF')
                        ->icon('heroicon-o-arrow-down-circle')
                        ->action(fn (Collection $records) => static::exportPdfBulk($records))
                        ->deselectRecordsAfterCompletion()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRetribusiRealizationReports::route('/'),
        ];
    }

    public static function exportPdf(RetribusiPembayaran $record)
    {
        // Load the necessary relationships
        $record->load(['items.retribusi', 'pedagang', 'user', 'pasar']);

        $pdf = Pdf::loadView('pdf.retribusi-realization-single', ['record' => $record]);
        // $pdf->setPaper([0, 0, 164.409, 136.063], 'portrait'); // 58mm x 48mm in points
        $pdf->setPaper([0, 0, 164.409, 170.079], 'portrait'); // Updated height to 60mm in points
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'retribusi-receipt.pdf');
    }

    public static function exportPdfBulk($records)
    {
        $pdf = Pdf::loadView('pdf.retribusi-realization-bulk', ['records' => $records]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'retribusi-realization-report-bulk.pdf');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user && $user->hasRole('kolektor')) {
            $assignedPasarIds = $user->pasars->pluck('id');
            return $query->whereHas('pedagang', function ($q) use ($assignedPasarIds) {
                $q->whereIn('pasar_id', $assignedPasarIds);
            });
        }

        return $query;
    }
}
