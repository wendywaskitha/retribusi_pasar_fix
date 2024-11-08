<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Desa;
use Filament\Tables;
use App\Models\Pasar;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Pedagang;
use Filament\Forms\Form;
use App\Models\Kecamatan;
use App\Models\Retribusi;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use App\Models\RetribusiPembayaran;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\ActionSize;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PedagangResource;
use App\Filament\Widgets\RetribusiMonthlyChart;
use App\Filament\Widgets\RetribusiStatsOverview;
use App\Filament\Widgets\KolektorComparisonChart;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RetribusiPembayaranResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use App\Filament\Resources\RetribusiPembayaranResource\RelationManagers;
use App\Filament\Resources\RetribusiPembayaranResource\Api\Transformers\RetribusiPembayaranTransformer;

class RetribusiPembayaranResource extends Resource implements HasShieldPermissions
{

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'publish'
        ];
    }


    protected static ?string $model = RetribusiPembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $modelLabel = 'Penarikan Retribusi';

    protected static ?int $navigationSort = -1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pedagang_id')
                    ->relationship('pedagang', 'name', function (Builder $query) {
                        if (Auth::user()?->hasRole('kolektor')) {
                            $assignedPasarIds = Auth::user()->pasars->pluck('id');
                            return $query->whereIn('pasar_id', $assignedPasarIds);
                        }
                        return $query;
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm(
                        PedagangResource::getForm(),
                    )
                    ->createOptionUsing(function (array $data): int {
                        return Pedagang::create($data)->id;
                    })
                    ->reactive()
                    ->afterStateUpdated(function($state, Set $set) {
                        $pedagang = Pedagang::find($state);
                        $set('pasar_id', $pedagang->pasar_id ?? null);
                    }),

                Forms\Components\Select::make('pasar_id')
                    ->disabled()
                    ->dehydrated()
                    ->label('Pasar')
                    ->options(function () {
                        if (Auth::user()?->hasRole('kolektor')) {
                            return Auth::user()->pasars->pluck('name', 'id');
                        }
                        return Pasar::all()->pluck('name', 'id');
                    })
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_bayar')
                    ->native(false)
                    ->closeOnDateSelection()
                    ->default(now())
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'lunas' => 'Lunas',
                    ])
                    ->required(),

                Forms\Components\Repeater::make('items')
                    ->label('Jenis Retribusi yang dipungut')
                    ->relationship('items')
                    ->schema([
                        Forms\Components\Select::make('retribusi_id')
                            ->relationship('retribusi', 'name')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $retribusi = Retribusi::find($state);
                                    if ($retribusi) {
                                        $set('biaya', $retribusi->biaya);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('biaya')
                            ->required()
                            ->disabled()
                            ->dehydrated(true)
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                    ])
                    ->columns(2)
                    ->reactive()
                    ->default([]) // Add default empty array
                    ->afterStateUpdated(function ($state, callable $set) {
                        // $total = collect($state)->sum('biaya');
                        // $set('total_biaya', $total);
                        // Handle both empty state and filled state
                        $total = is_array($state) ? collect($state)->sum('biaya') : 0;
                        $set('total_biaya', $total);
                    })
                    ->minItems(0) // Allow zero items
                    ->createItemButtonLabel('Tambah Retribusi'), // Optional: customize button label,

                Forms\Components\TextInput::make('total_biaya')
                    ->disabled()
                    ->dehydrated(true)
                    ->prefix('Rp')
                    ->numeric()
                    ->default(0),

                Forms\Components\Placeholder::make('created_by')
                    ->label('Dibuat Oleh')
                    ->content(fn (RetribusiPembayaran $record): string => $record->user->name ?? '-')
                    ->visibleOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pedagang.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pasar.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->date()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'pending',
                        'success' => 'lunas',
                    ]),
                Tables\Columns\TextColumn::make('total_biaya')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal_bayar')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_bayar')
                            ->label('Tanggal Bayar')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['tanggal_bayar'],
                            fn (Builder $query, $date): Builder => $query->whereDate('tanggal_bayar', $date),
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['tanggal_bayar']) {
                            return null;
                        }
                        return 'Tanggal: ' . Carbon::parse($data['tanggal_bayar'])->translatedFormat('d F Y');
                    })
                    ->default(),
                Tables\Filters\SelectFilter::make('pasar_id')
                    ->label('Pasar')
                    ->options(function () {
                        if (Auth::user()?->hasRole('kolektor')) {
                            return Auth::user()->pasars->pluck('name', 'id');
                        }
                        return Pasar::all()->pluck('name', 'id');
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Action::make('export_pdf')
                //     ->label('Struk')
                //     ->icon('heroicon-o-printer')
                //     ->action(fn (RetribusiPembayaran $record) => static::exportPdf($record))
                Action::make('preview_struk')
                    ->label('Struk')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Preview Struk')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->size(ActionSize::Large)
                    ->action(function (RetribusiPembayaran $record) {
                        return static::exportPdf($record);
                    })
                    ->modalContent(function (RetribusiPembayaran $record): View {
                        $record->load(['items.retribusi', 'pedagang', 'user', 'pasar']);
                        return view('filament.resources.retribusi-pembayaran.preview-struk', [
                            'record' => $record,
                        ]);
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal_bayar', 'desc');
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
            'index' => Pages\ListRetribusiPembayarans::route('/'),
            'create' => Pages\CreateRetribusiPembayaran::route('/create'),
            'edit' => Pages\EditRetribusiPembayaran::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()?->hasRole('kolektor')) {
            $assignedPasarIds = Auth::user()->pasars->pluck('id');
            return $query->whereHas('pasar', function ($query) use ($assignedPasarIds) {
                $query->whereIn('id', $assignedPasarIds);
            });
        }

        return $query;
    }

    public static function exportPdf(RetribusiPembayaran $record)
    {
        $record->load(['items.retribusi', 'pedagang', 'user', 'pasar']);

        $pdf = Pdf::loadView('pdf.retribusi-realization-single', ['record' => $record]);
        $pdf->setPaper([0, 0, 164.409, 170.079], 'portrait'); // 80mm x 50mm in points

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'retribusi-receipt.pdf');
    }

    public static function getApiTransformer()
    {
        return RetribusiPembayaranTransformer::class;
    }

}
