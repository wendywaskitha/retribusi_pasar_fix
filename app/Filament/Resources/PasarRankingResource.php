<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pasar;
use Filament\Forms\Form;
use Illuminate\View\View;
use Filament\Tables\Table;
use App\Models\PasarRanking;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use App\Models\RetribusiPembayaran;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PasarRankingResource\Pages;
use App\Filament\Resources\PasarRankingResource\RelationManagers;

class PasarRankingResource extends Resource
{
    protected static ?string $model = Pasar::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $modelLabel = 'Perolehan Per Pasar';

    protected static ?string $navigationGroup = 'Laporan';


    public static function table(Table $table): Table
    {

        return $table
            ->heading(function (Table $table): string {
                $filterState = $table->getFilter('month_year')->getState();

                if ($filterState && isset($filterState['month']) && isset($filterState['year'])) {
                    $month = $filterState['month'];
                    $year = $filterState['year'];

                    $monthName = Carbon::create(2000, $month, 1)->locale('id')->monthName;

                    return "Peringkat Retribusi Pasar untuk Bulan {$monthName} {$year}";
                }

                // Default heading when no filter is applied
                $currentDate = Carbon::now()->locale('id');
                return "Peringkat Retribusi Pasar untuk Bulan {$currentDate->monthName} {$currentDate->year}";
            })
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('Rank')
                    ->getStateUsing(function ($record, $rowLoop) {
                        return $rowLoop->iteration;
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->label('Pasar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kecamatan.name')
                    ->label('Kecamatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('desa.name')
                    ->label('Desa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_retribusi')
                    ->label('Total Retribusi')
                    ->money('IDR')
                    ->summarize(Sum::make()->money('IDR'))
                    ->sortable(),
            ])
            ->defaultSort('total_retribusi', 'desc')
            ->filters([
                Filter::make('month_year')
                ->form([
                    Forms\Components\Select::make('month')
                        ->options([
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ])
                        ->label('Bulan'),
                    Forms\Components\Select::make('year')
                        ->options(array_combine(
                            range(date('Y') - 5, date('Y') + 5),
                            range(date('Y') - 5, date('Y') + 5)
                        ))
                        ->label('Tahun'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['month'] && $data['year'],
                        function (Builder $query) use ($data): Builder {
                            return $query->whereHas('retribusiPembayarans', function (Builder $subQuery) use ($data) {
                                $subQuery->whereMonth('tanggal_bayar', $data['month'])
                                         ->whereYear('tanggal_bayar', $data['year']);
                            });
                        }
                    );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['month'] ?? null) {
                        $monthName = Carbon::create(2000, $data['month'], 1)->locale('id')->monthName;
                        $indicators['month'] = "Bulan: {$monthName}";
                    }

                    if ($data['year'] ?? null) {
                        $indicators['year'] = "Tahun: {$data['year']}";
                    }

                    return $indicators;
                })
            ])
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->headerActions([
                Action::make('export_monthly_report')
                    ->label('Export Monthly Report')
                    ->icon('heroicon-o-document-text')
                    ->form([
                        Forms\Components\DatePicker::make('month')
                            ->label('Select Month')
                            ->default(now())
                            ->displayFormat('F Y')
                            ->format('Y-m')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        return static::exportMonthlyReport($data['month']);
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPasarRankings::route('/'),
            'view' => Pages\ViewPasarRanking::route('/{record}'),
        ];
    }

    protected static function getYearOptions(): array
    {
        $currentYear = now()->year;
        $years = range($currentYear - 5, $currentYear + 1);
        return array_combine($years, $years);
    }


    public static function getEloquentQuery(): Builder
    {
        $month = request()->input('tableFilters.month');
        $year = request()->input('tableFilters.year');

        return Pasar::withTotalRetribusi($month, $year);
    }

    public static function exportMonthlyReport($selectedMonth = null)
    {
        $currentMonth = $selectedMonth ? Carbon::parse($selectedMonth) : Carbon::now();
        // $currentMonth = Carbon::now();

        // Query to get all pasars with their rankings for current month
        $pasars = Pasar::with(['pedagangs', 'retribusiPembayarans' => function ($query) use ($currentMonth) {
            $query->whereYear('tanggal_bayar', $currentMonth->year)
                  ->whereMonth('tanggal_bayar', $currentMonth->month);
        }])
        ->get()
        ->map(function ($pasar) {
            // Calculate total retribusi for current month
            $totalRetribusi = $pasar->retribusiPembayarans->sum('total_biaya');

            return [
                'name' => $pasar->name,
                'total_pedagang' => $pasar->pedagangs->count(),
                'total_retribusi' => $totalRetribusi,
                // Add more data as needed
            ];
        })
        ->sortByDesc('total_retribusi')
        ->values();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.pasar-ranking-report', [
            'pasars' => $pasars,
            'month' => $currentMonth->format('F Y')
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'pasar-ranking-report-' . $currentMonth->format('F-Y') . '.pdf'
        );
    }
}
