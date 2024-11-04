<?php

namespace App\Filament\Resources\TargetRetribusiResource\Pages;

use App\Filament\Resources\TargetRetribusiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTargetRetribusi extends EditRecord
{
    protected static string $resource = TargetRetribusiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
