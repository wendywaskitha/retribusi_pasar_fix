<?php
namespace App\Filament\Resources\TargetRetribusiResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\TargetRetribusiResource;
use Illuminate\Routing\Router;


class TargetRetribusiApiService extends ApiService
{
    protected static string | null $resource = TargetRetribusiResource::class;

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
