<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RetribusiReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use App\Models\RetribusiPembayaran;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Maatwebsite\Excel\Events\AfterSheet;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RetribusiReportResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\RetribusiReportResource\RelationManagers;

class RetribusiReportResource extends Resource
{
    protected static ?string $model = RetribusiPembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Laporan Retribusi';

    protected static ?string $modelLabel = 'Laporan Retribusi';

    protected static ?string $slug = 'reports/retribusi';

    protected static ?string $navigationGroup = 'Laporan';

    protected static bool $shouldRegisterNavigation = false;


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pedagang.name')
                    ->label('Pedagang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pasar.name')
                    ->label('Pasar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_biaya')
                    ->label('Jumlah Bayar')
                    ->money('IDR')
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('IDR'),
                    ]),
            ])
            ->filters([
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_bayar', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_bayar', '<=', $date),
                            );
                    }),
                SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(
                        RetribusiPembayaran::selectRaw('YEAR(tanggal_bayar) as year')
                            ->distinct()
                            ->pluck('year', 'year')
                            ->toArray()
                    ),
                SelectFilter::make('month')
                    ->label('Bulan')
                    ->options([
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ])
            ])
            ->bulkActions([
                ExportBulkAction::make()->exports([
                    ExcelExport::make('excel')
                        ->fromTable()
                        ->withFilename('laporan_retribusi_' . date('Y-m-d'))
                ])
            ])
            ->headerActions([
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->action(function ($livewire) {
                        $query = static::getModel()::query();

                        $data = $query->with(['pedagang', 'pasar'])->get();
                        $total = $data->sum('total_biaya');

                        $pdf = Pdf::loadView('reports.retribusi', [
                            'data' => $data,
                            'total' => $total,
                            'filters' => [],
                        ]);

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'laporan_retribusi_' . date('Y-m-d') . '.pdf');
                    })
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
            'index' => Pages\ListRetribusiReports::route('/'),
        ];
    }
}
