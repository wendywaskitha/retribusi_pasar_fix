<?php

namespace App\Filament\Resources\RealisasiPerPasarResource\Pages;

use App\Filament\Resources\RealisasiPerPasarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRealisasiPerPasar extends EditRecord
{
    protected static string $resource = RealisasiPerPasarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
