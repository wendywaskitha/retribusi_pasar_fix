<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Desa;
use Filament\Tables;
use App\Models\Pasar;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Kecamatan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PasarResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PasarResource\RelationManagers;

class PasarResource extends Resource
{
    protected static ?string $model = Pasar::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Pasar')
                        ->required(),
                    Forms\Components\Select::make('kecamatan_id')
                        ->label('Kecamatan')
                        ->options(Kecamatan::query()->pluck('name', 'id'))
                        ->live()
                        ->preload()
                        ->searchable()
                        ->native(false)
                        ->required(),
                    Forms\Components\Select::make('desa_id')
                        ->label('Desa/Kelurahan')
                        ->options(fn (Get $get): Collection => Desa::query()
                            ->where('kecamatan_id', $get('kecamatan_id'))
                            ->pluck('name', 'id'))
                        ->preload()
                        ->searchable()
                        ->native(false)
                        ->required(),
                    Forms\Components\Textarea::make('address')
                        ->label('Alamat')
                        ->columnSpanFull(),
                ])->columnSpan(['lg' => 2]),
                Section::make([
                    Map::make('location')
                        ->label('Lokasi')
                        ->columnSpanFull()
                        ->defaultLocation(latitude: -4.810284653427349, longitude: 122.40440022985612)
                        ->afterStateUpdated(function (Set $set, ?array $state): void {
                            $set('latitude',  $state['lat']);
                            $set('longitude', $state['lng']);
                        })
                        ->afterStateHydrated(function ($state, $record, Set $set): void {
                            $set('location', ['lat' => $record?->latitude, 'lng' => $record?->longitude]);
                        })
                        ->extraStyles([
                            'min-height: 50vh',
                            'border-radius: 10px'
                        ])
                        // ->liveLocation(true, true, 5000)
                        ->showMarker()
                        ->markerColor("#22c55eff")
                        ->showFullscreenControl()
                        ->showZoomControl()
                        ->draggable()
                        ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                        ->zoom(13)
                        ->showMyLocationButton(),
                    Forms\Components\TextInput::make('latitude')
                        ->readOnly()
                        ->numeric(),
                    Forms\Components\TextInput::make('longitude')
                        ->readOnly()
                        ->numeric(),
                ])->columnSpan(['lg' => 1])
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Pasar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kecamatan.name')
                    ->label('Kecamatan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pedagangs_count')->counts('pedagangs')->label('Jumlah Pedagang')->alignCenter(),
                // Tables\Columns\TextColumn::make('desa.name')
                //     ->label('Desa/Kelurahan')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPasars::route('/'),
            'create' => Pages\CreatePasar::route('/create'),
            'edit' => Pages\EditPasar::route('/{record}/edit'),
        ];
    }
}
