<?php

namespace App\Filament\Resources\PasarRankingResource\Pages;

use App\Filament\Resources\PasarRankingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPasarRanking extends EditRecord
{
    protected static string $resource = PasarRankingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
