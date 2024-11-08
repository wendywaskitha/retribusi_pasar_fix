<?php
namespace App\Filament\Resources\RetribusiPembayaranResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\RetribusiPembayaranResource;
use Illuminate\Routing\Router;


class RetribusiPembayaranApiService extends ApiService
{
    protected static string | null $resource = RetribusiPembayaranResource::class;

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
