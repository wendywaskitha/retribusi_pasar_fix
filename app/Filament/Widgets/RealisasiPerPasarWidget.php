<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Pasar;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

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
                    ->sortable(),
                Tables\Columns\TextColumn::make('pedagang_sudah_bayar')
                    ->label('Sudah Bayar')
                    ->getStateUsing(fn ($record) => $this->getPedagangSudahBayar($record->id, $this->filterDate))
                    ->color('success')
                    ->summarize(
                        Tables\Columns\Summarizers\Summarizer::make()
                            ->label('Total Sudah Bayar')
                            ->using(fn ($query) => $this->getTotalSudahBayar($query))
                    ),
                Tables\Columns\TextColumn::make('belum_bayar')
                    ->label('Belum Bayar')
                    ->getStateUsing(function ($record) {
                        $belumBayar = $record->total_pedagang - $this->getPedagangSudahBayar($record->id, $this->filterDate);
                        return number_format($belumBayar, 0, ',', '.'); // Indonesian number format
                    })
                    ->color('danger')
                    ->summarize(
                        Tables\Columns\Summarizers\Summarizer::make()
                            ->label('Total Belum Bayar')
                            ->using(fn ($query) => $this->getTotalBelumBayar($query))
                    ),
                Tables\Columns\TextColumn::make('total_realisasi')
                    ->label('Total Realisasi')
                    ->getStateUsing(fn ($record) => 'Rp ' . number_format($this->getTotalRealisasi($record->id, $this->filterDate), 0, ',', '.'))
                    ->summarize(
                        Tables\Columns\Summarizers\Summarizer::make()
                            ->label('Total Realisasi Keseluruhan')
                            ->using(fn ($query) => 'Rp ' . $this->getTotalRealisasiKeseluruhan($query))
                    ),
                Tables\Columns\TextColumn::make('persentase_realisasi')
                    ->label('Persentase')
                    ->getStateUsing(fn ($record) => $this->getPersentaseRealisasi($record->id, $this->filterDate))
                    ->color(function ($state) {
                        $percentage = floatval(str_replace('%', '', $state));
                        if ($percentage >= 80) return 'success';
                        if ($percentage >= 50) return 'warning';
                        return 'danger';
                    })
                    ->summarize(
                        Tables\Columns\Summarizers\Summarizer::make()
                            ->label('Rata-rata Persentase')
                            ->using(fn ($query) => $this->getRataRataPersentase($query))
                    ),
            ])
            ->defaultSort('name', 'asc')
            ->paginated(false)
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->form([
                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->default(now())
                            ->maxDate(now())
                            // ->native(false)
                            // ->closeOnDateSelection(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $this->filterDate = $data['date'] ?? now()->toDateString();
                        return $query;
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['date'] ?? null) {
                            return 'Tanggal: ' . Carbon::parse($data['date'])->format('d M Y');
                        }

                        return null;
                    }),
            ])
            // ->filtersFormColumns(3)
            ->striped();
    }

    protected function getTableQuery(): Builder
    {
        return Pasar::query()
            ->withCount('pedagangs as total_pedagang');
    }

    protected function getPedagangSudahBayar($pasarId, $date): int
    {
        return DB::table('pedagangs')
            ->join('retribusi_pembayarans', 'pedagangs.id', '=', 'retribusi_pembayarans.pedagang_id')
            ->where('pedagangs.pasar_id', $pasarId)
            ->whereDate('retribusi_pembayarans.tanggal_bayar', $date)
            ->distinct('pedagangs.id')
            ->count('pedagangs.id');
    }

    protected function getTotalRealisasi($pasarId, $date): float
    {
        return DB::table('retribusi_pembayarans')
            ->join('pedagangs', 'retribusi_pembayarans.pedagang_id', '=', 'pedagangs.id')
            ->where('pedagangs.pasar_id', $pasarId)
            ->whereDate('retribusi_pembayarans.tanggal_bayar', $date)
            ->sum('retribusi_pembayarans.total_biaya');
    }

    protected function getPersentaseRealisasi($pasarId, $date): string
    {
        $totalPedagang = DB::table('pedagangs')->where('pasar_id', $pasarId)->count();
        $pedagangSudahBayar = $this->getPedagangSudahBayar($pasarId, $date);

        if ($totalPedagang == 0) return '0%';

        return number_format(($pedagangSudahBayar / $totalPedagang) * 100, 2) . '%';
    }

    protected function getTotalSudahBayar($query)
    {
        $pasarIds = $query->pluck('id');
        return DB::table('pedagangs')
            ->join('retribusi_pembayarans', 'pedagangs.id', '=', 'retribusi_pembayarans.pedagang_id')
            ->whereIn('pedagangs.pasar_id', $pasarIds)
            ->whereDate('retribusi_pembayarans.tanggal_bayar', $this->filterDate ?? now()->toDateString())
            ->distinct('pedagangs.id')
            ->count('pedagangs.id');
    }

    protected function getTotalBelumBayar($query)
    {
        $totalPedagang = $query->sum('total_pedagang');
        $totalSudahBayar = $this->getTotalSudahBayar($query);
        $belumBayar = $totalPedagang - $totalSudahBayar;
        return number_format($belumBayar, 0, ',', '.'); // Indonesian number format
    }

    protected function getTotalRealisasiKeseluruhan($query)
    {
        $pasarIds = $query->pluck('id');
        $totalRealisasi = DB::table('retribusi_pembayarans')
            ->join('pedagangs', 'retribusi_pembayarans.pedagang_id', '=', 'pedagangs.id')
            ->whereIn('pedagangs.pasar_id', $pasarIds)
            ->whereDate('retribusi_pembayarans.tanggal_bayar', $this->filterDate ?? now()->toDateString())
            ->sum('retribusi_pembayarans.total_biaya');

        return number_format($totalRealisasi, 0, ',', '.'); // Format as IDR
    }

    protected function getRataRataPersentase($query)
    {
        $totalPasar = $query->count();
        $totalPedagang = $query->sum('total_pedagang');
        $totalSudahBayar = $this->getTotalSudahBayar($query);

        if ($totalPedagang == 0) return '0%';

        return number_format(($totalSudahBayar / $totalPedagang) * 100, 2) . '%';
    }
}
