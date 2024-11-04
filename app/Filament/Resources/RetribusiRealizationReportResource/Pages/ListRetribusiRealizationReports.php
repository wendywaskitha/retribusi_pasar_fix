<?php

namespace App\Filament\Resources\RetribusiRealizationReportResource\Pages;

use App\Filament\Resources\RetribusiRealizationReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRetribusiRealizationReports extends ListRecords
{
    protected static string $resource = RetribusiRealizationReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
