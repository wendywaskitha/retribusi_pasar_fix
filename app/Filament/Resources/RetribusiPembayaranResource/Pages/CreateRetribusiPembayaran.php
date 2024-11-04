<?php

namespace App\Filament\Resources\RetribusiPembayaranResource\Pages;

use App\Filament\Resources\RetribusiPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRetribusiPembayaran extends CreateRecord
{
    protected static string $resource = RetribusiPembayaranResource::class;

    protected function afterCreate(): void
    {
        // Update the total after creation
        $this->record->updateTotal();
    }
}
