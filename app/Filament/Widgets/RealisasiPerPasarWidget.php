<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Pasar;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class RealisasiPerPasarWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return 'Realisasi Per Pasar - ' . Carbon::today()->format('d F Y');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pasar::query()
                    ->withCount(['pedagangs as total_pedagang'])
                    ->withCount(['pedagangs as pedagang_sudah_bayar' => function (Builder $query) {
                        $query->whereHas('retribusiPembayarans', function ($q) {
                            $q->whereDate('tanggal_bayar', Carbon::today());
                        });
                    }])
                    ->withSum(['retribusiPembayarans as total_realisasi' => function (Builder $query) {
                        $query->whereDate('tanggal_bayar', Carbon::today());
                    }], 'total_biaya')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Pasar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_pedagang')
                    ->label('Total Pedagang')
                    ->sortable()
                    ->summarize(
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total Keseluruhan')
                    ),
                Tables\Columns\TextColumn::make('pedagang_sudah_bayar')
                    ->label('Sudah Bayar')
                    ->sortable()
                    ->color('success')
                    ->summarize(
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total Sudah Bayar')
                    ),
                Tables\Columns\TextColumn::make('belum_bayar')
                    ->label('Belum Bayar')
                    ->getStateUsing(fn ($record) => $record->total_pedagang - $record->pedagang_sudah_bayar)
                    ->sortable()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('total_realisasi')
                    ->label('Total Realisasi')
                    ->money('IDR')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->total_realisasi ?? 0; // Return 0 if empty
                    })
                    ->summarize(
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('IDR')
                            ->label('Total Realisasi Keseluruhan')
                            ->using(function ($query) {
                                return $query->sum('total_realisasi') ?: 0; // Ensure 0 if sum is empty
                            })
                    ),
                Tables\Columns\TextColumn::make('persentase_realisasi')
                    ->label('Persentase')
                    ->getStateUsing(function ($record) {
                        if ($record->total_pedagang == 0) return '0%';
                        return number_format(($record->pedagang_sudah_bayar / $record->total_pedagang) * 100, 2) . '%';
                    })
                    ->sortable()
                    ->color(function ($state) {
                        $percentage = floatval(str_replace('%', '', $state));
                        if ($percentage >= 80) return 'success';
                        if ($percentage >= 50) return 'warning';
                        return 'danger';
                    }),
            ])
            ->defaultSort('name', 'asc')
            ->paginated(false)
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->form([
                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->default(now())
                            ->maxDate(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['date'],
                            fn (Builder $query, $date): Builder => $query
                                ->withCount(['pedagangs as pedagang_sudah_bayar' => function (Builder $query) use ($date) {
                                    $query->whereHas('retribusiPembayarans', function (Builder $query) use ($date) {
                                        $query->whereDate('tanggal_bayar', $date);
                                    });
                                }])
                                ->withSum(['retribusiPembayarans as total_realisasi' => function (Builder $query) use ($date ) {
                                    $query->whereDate('tanggal_bayar', $date);
                                }], 'total_biaya')
                        );
                    })
            ])
            ->filtersFormColumns(3);
    }
}
