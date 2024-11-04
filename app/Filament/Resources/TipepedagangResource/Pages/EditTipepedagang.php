<?php

namespace App\Filament\Resources\TipepedagangResource\Pages;

use App\Filament\Resources\TipepedagangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipepedagang extends EditRecord
{
    protected static string $resource = TipepedagangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
