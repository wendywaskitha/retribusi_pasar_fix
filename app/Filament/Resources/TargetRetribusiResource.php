<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TargetRetribusiResource\Pages;
use App\Filament\Resources\TargetRetribusiResource\RelationManagers;
use App\Models\TargetRetribusi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TargetRetribusiResource extends Resource
{
    protected static ?string $model = TargetRetribusi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tahun')
                    ->options(collect(range(date('Y') - 1, date('Y') + 5))->mapWithKeys(fn ($year)=> [$year => $year]))
                    ->required(),
                Forms\Components\TextInput::make('target_amount')
                    ->label('Target Retribusi')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahun'),
                Tables\Columns\TextColumn::make('target_amount')
                    ->money('idr'),
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTargetRetribusis::route('/'),
            'create' => Pages\CreateTargetRetribusi::route('/create'),
            'edit' => Pages\EditTargetRetribusi::route('/{record}/edit'),
        ];
    }
}
