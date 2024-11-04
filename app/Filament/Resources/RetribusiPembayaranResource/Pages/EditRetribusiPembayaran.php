<?php

namespace App\Filament\Resources\RetribusiPembayaranResource\Pages;

use App\Filament\Resources\RetribusiPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRetribusiPembayaran extends EditRecord
{
    protected static string $resource = RetribusiPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Update the total after saving
        $this->record->updateTotal();
    }
}
