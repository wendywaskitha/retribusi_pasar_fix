<?php

namespace App\Filament\Resources\RetribusiRealizationReportResource\Pages;

use App\Filament\Resources\RetribusiRealizationReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRetribusiRealizationReport extends EditRecord
{
    protected static string $resource = RetribusiRealizationReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
