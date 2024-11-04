<?php

namespace App\Filament\Widgets;

use App\Models\Pasar;
use Filament\Tables;
use Filament\Forms;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RealisasiPerPasarWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected ?string $filterDate = null;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Pasar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_pedagang')
                    ->label('Total Pedagang')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $this->getTotalPedagang($record);
                    }),
                Tables\Columns\TextColumn::make('pedagang_sudah_bayar')
                    ->label('Sudah Bayar')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $this->getPedagangSudahBayar($record, $this->filterDate);
                    })
                    ->color('success'),
                Tables\Columns\TextColumn::make('belum_bayar')
                    ->label('Belum Bayar')
                    ->getStateUsing(fn ($record) => $this->getTotalPedagang($record) - $this->getPedagangSudahBayar($record, $this->filterDate))
                    ->sortable()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('total_realisasi')
                    ->label('Total Realisasi')
                    ->money('IDR')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $this->getTotalRealisasi($record, $this->filterDate);
                    }),
                Tables\Columns\TextColumn::make('persentase_realisasi')
                    ->label('Persentase')
                    ->getStateUsing(function ($record) {
                        return $this->getPersentaseRealisasi($record, $this->filterDate);
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
                        $this->filterDate = $data['date'] ?? now()->toDateString();
                        return $query;
                    })
            ])
            ->filtersFormColumns(3);
    }

    protected function getTableQuery(): Builder
    {
        return Pasar::query();
    }

    protected function getTotalPedagang($record): int
    {
        return $record->pedagangs()->count();
    }

    protected function getPedagangSudahBayar($record, $date): int
    {
        return $record->pedagangs()
            ->whereHas('retribusiPembayarans', function ($query) use ($date) {
                $query->whereDate('tanggal_bayar', $date);
            })
            ->count();
    }

    protected function getTotalRealisasi($record, $date): float
    {
        return $record->pedagangs()
            ->join('retribusi_pembayarans', 'pedagangs.id', '=', 'retribusi_pembayarans.pedagang_id')
            ->whereDate('tanggal_bayar', $date)
            ->sum('total_biaya');
    }

    protected function getPersentaseRealisasi($record, $date): string
    {
        $totalPedagang = $this->getTotalPedagang($record);
        $pedagangSudahBayar = $this->getPedagangSudahBayar($record, $date);

        if ($totalPedagang == 0) return '0%';

        return number_format(($pedagangSudahBayar / $totalPedagang) * 100, 2) . '%';
    }

    protected function getTableHeading(): string
    {
        $date = $this->filterDate ?? now()->toDateString();
        return 'Realisasi Per Pasar - ' . Carbon::parse($date)->format('d F Y');
    }

    protected function getFooter(): ?View
    {
        $totalPedagang = Pasar::withCount('pedagangs')->get()->sum('pedagangs_count');
        $totalSudahBayar = Pasar::withCount(['pedagangs' => function ($query) {
            $query->whereHas('retribusiPembayarans', function ($subQuery) {
                $subQuery->whereDate('tanggal_bayar', $this->filterDate ?? now()->toDateString());
            });
        }])->get()->sum('pedagangs_count');
        $totalRealisasi = Pasar::withSum(['retribusiPembayarans' => function ($query) {
            $query->whereDate('tanggal_bayar', $this->filterDate ?? now()->toDateString());
        }], 'total_biaya')->get()->sum('retribusi_pembayarans_sum_total_biaya');

        return view('filament.widgets.realisasi-per-pasar-footer', [
            'totalPedagang' => $totalPedagang,
            'totalSudahBayar' => $totalSudahBayar,
            'totalBelumBayar' => $totalPedagang - $totalSudahBayar,
            'totalRealisasi' => $totalRealisasi,
            'persentaseRealisasi' => $totalPedagang > 0 ? number_format(($totalSudahBayar / $totalPedagang) * 100, 2) . '%' : '0%',
        ]);
    }
}
