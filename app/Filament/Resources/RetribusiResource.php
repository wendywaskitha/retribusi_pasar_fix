<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RetribusiResource\Pages;
use App\Filament\Resources\RetribusiResource\RelationManagers;
use App\Models\Retribusi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RetribusiResource extends Resource
{
    protected static ?string $model = Retribusi::class;

    protected static ?string $navigationIcon = 'heroicon-s-paper-clip';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Retribusi')
                    ->required(),
                Forms\Components\TextInput::make('biaya')
                    ->label('Biaya')
                    ->prefix('Rp.')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Retribusi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('biaya')
                    ->money('IDR')
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
            'index' => Pages\ListRetribusis::route('/'),
            'create' => Pages\CreateRetribusi::route('/create'),
            'edit' => Pages\EditRetribusi::route('/{record}/edit'),
        ];
    }
}
