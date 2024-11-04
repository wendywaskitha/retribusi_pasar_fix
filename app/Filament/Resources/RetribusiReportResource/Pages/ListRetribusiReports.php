<?php

namespace App\Filament\Resources\RetribusiReportResource\Pages;

use App\Filament\Resources\RetribusiReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRetribusiReports extends ListRecords
{
    protected static string $resource = RetribusiReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
