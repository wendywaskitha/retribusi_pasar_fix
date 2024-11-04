<?php

namespace App\Filament\Resources\TipepedagangResource\Pages;

use App\Filament\Resources\TipepedagangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipepedagangs extends ListRecords
{
    protected static string $resource = TipepedagangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
