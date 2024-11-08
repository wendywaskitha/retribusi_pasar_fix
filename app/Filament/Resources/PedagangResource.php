<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Desa;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Pedagang;
use Filament\Forms\Form;
use App\Models\Kecamatan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PedagangResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PedagangResource\RelationManagers;
use App\Filament\Resources\PedagangResource\Api\Transformers\PedagangTransformer;

class PedagangResource extends Resource
{
    protected static ?string $model = Pedagang::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';


    public static function getForm()
    {
        return [
            Section::make([
                Forms\Components\TextInput::make('name')
                    ->label('Nama lengkap Pedagang')
                    ->required(),
                Forms\Components\TextInput::make('nik')
                ->label('Nomor Induk Kependudukan (NIK)'),
                Forms\Components\Textarea::make('alamat')
                    ->columnSpanFull(),
                Forms\Components\Select::make('tipepedagang_id')
                    ->label('Tipe Pedagang')
                    ->relationship('tipepedagang', 'name')
                    ->required(),
                Forms\Components\Select::make('kecamatan_id')
                    ->label('Kecamatan')
                    ->options(Kecamatan::query()->pluck('name', 'id'))
                    ->live()
                    ->afterStateUpdated(function (callable $set) {
                        $set('desa_id', null); // Clear desa_id when kecamatan changes
                    })
                    ->preload()
                    ->searchable()
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('desa_id')
                    ->label('Desa/Kelurahan')
                    ->options(function (callable $get) {
                        $kecamatanId = $get('kecamatan_id');

                        if (!$kecamatanId) {
                            return [];
                        }

                        return Desa::query()
                            ->where('kecamatan_id', $kecamatanId)
                            ->pluck('name', 'id');
                    })
                    ->live()
                    ->preload()
                    ->searchable()
                    ->native(false)
                    ->required()
                    ->placeholder('Select Desa/Kelurahan')
                    ->getOptionLabelUsing(fn ($value) => Desa::find($value)?->name ?? '')
                    ->loadingMessage('Loading desa...')
                    ->noSearchResultsMessage('No desa found.')
                    ->searchingMessage('Searching desa...'), // Ensure it shows the name
                Forms\Components\Select::make('pasar_id')
                    ->relationship('pasar', 'name', function (Builder $query) {
                        $user = Auth::user();
                        if ($user?->hasRole('kolektor')) {
                            return $query->whereIn('id', $user->pasars->pluck('id'));
                        }
                        return $query;
                    })
                    ->preload()
                    ->searchable()
                    ->native(false)
                    ->required(),
            ])->inlineLabel()
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                self::getForm()
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();
                if ($user?->hasRole('kolektor')) {
                    $query->whereIn('pasar_id', $user->pasars->pluck('id'));
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipepedagang.name')
                    ->label('Tipe Pedagang')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kecamatan.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('desa.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pasar.name')
                    ->numeric()
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
            'index' => Pages\ListPedagangs::route('/'),
            'create' => Pages\CreatePedagang::route('/create'),
            'edit' => Pages\EditPedagang::route('/{record}/edit'),
        ];
    }

    public static function getApiTransformer()
    {
        return PedagangTransformer::class;
    }
}
