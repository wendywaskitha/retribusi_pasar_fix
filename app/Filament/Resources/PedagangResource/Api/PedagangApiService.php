<?php
namespace App\Filament\Resources\PedagangResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\PedagangResource;
use Illuminate\Routing\Router;


class PedagangApiService extends ApiService
{
    protected static string | null $resource = PedagangResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
