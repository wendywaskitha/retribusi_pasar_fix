<?php

namespace App\Filament\Resources\RetribusiResource\Pages;

use App\Filament\Resources\RetribusiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRetribusis extends ListRecords
{
    protected static string $resource = RetribusiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
