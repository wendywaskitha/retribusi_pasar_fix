<?php

namespace App\Filament\Resources\TargetRetribusiResource\Pages;

use App\Filament\Resources\TargetRetribusiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTargetRetribusis extends ListRecords
{
    protected static string $resource = TargetRetribusiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
