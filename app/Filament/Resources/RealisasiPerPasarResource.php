<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pasar;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use App\Models\RealisasiPerPasar;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RealisasiPerPasarResource\Pages;
use App\Filament\Resources\RealisasiPerPasarResource\RelationManagers;

class RealisasiPerPasarResource extends Resource
{
    protected static ?string $model = Pasar::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Realisasi Per Pasar';

    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pasar')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_pedagang')
                    ->label('Total Pedagang')
                    ->getStateUsing(function (Pasar $record): int {
                        return $record->pedagangs()->count();
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('pedagang_sudah_bayar')
                    ->label('Sudah Bayar')
                    ->getStateUsing(function (Pasar $record): int {
                        return $record->pedagangs()
                            ->whereHas('retribusiPembayarans', function ($query) {
                                $query->whereDate('tanggal_bayar', Carbon::today());
                            })
                            ->count();
                    })
                    ->color('success'),

                Tables\Columns\TextColumn::make('pedagang_belum_bayar')
                    ->label('Belum Bayar')
                    ->getStateUsing(function (Pasar $record): int {
                        $totalPedagang = $record->pedagangs()->count();
                        $sudahBayar = $record->pedagangs()
                            ->whereHas('retribusiPembayarans', function ($query) {
                                $query->whereDate('tanggal_bayar', Carbon::today());
                            })
                            ->count();
                        return $totalPedagang - $sudahBayar;
                    })
                    ->color('danger'),

                Tables\Columns\TextColumn::make('total_realisasi')
                    ->label('Total Realisasi')
                    ->getStateUsing(function (Pasar $record): string {
                        $total = $record->retribusiPembayarans()
                            ->whereDate('tanggal_bayar', Carbon::today())
                            ->sum('total_biaya');
                        return 'Rp ' . number_format($total, 0, ',', '.');
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('persentase_realisasi')
                    ->label('Persentase')
                    ->getStateUsing(function (Pasar $record): string {
                        $totalPedagang = $record->pedagangs()->count();
                        if ($totalPedagang === 0) return '0%';

                        $sudahBayar = $record->pedagangs()
                            ->whereHas('retribusiPembayarans', function ($query) {
                                $query->whereDate('tanggal_bayar', Carbon::today());
                            })
                            ->count();

                        $percentage = ($sudahBayar / $totalPedagang) * 100;
                        return number_format($percentage, 1) . '%';
                    })
                    ->color(function (string $state): string {
                        $percentage = floatval($state);
                        if ($percentage >= 80) return 'success';
                        if ($percentage >= 50) return 'warning';
                        return 'danger';
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal')
                            ->default(now())
                            ->maxDate(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['date'],
                            fn (Builder $query, $date): Builder => $query
                                ->withCount(['pedagangs as paid_count' => function (Builder $query) use ($date) {
                                    $query->whereHas('retribusiPembayarans', function (Builder $query) use ($date) {
                                        $query->whereDate('tanggal_bayar', $date);
                                    });
                                }])
                                ->withSum(['retribusiPembayarans as total_realisasi' => function (Builder $query) use ($date) {
                                    $query->whereDate('tanggal_bayar', $date);
                                }], 'total_biaya')
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['date']) {
                            return null;
                        }

                        return 'Tanggal: ' . Carbon::parse($data['date'])->format('d F Y');
                    }),
            ])
            ->defaultSort('name')
            ->poll('10s') // Auto refresh every 10 seconds
            ->striped()
            ->paginated([25, 50, 100])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListRealisasiPerPasars::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('pedagangs')
            ->withSum(['retribusiPembayarans' => function ($query) {
                $query->whereDate('tanggal_bayar', Carbon::today());
            }], 'total_biaya');
    }
}
