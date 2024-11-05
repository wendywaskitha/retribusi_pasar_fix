<?php
namespace App\Filament\Resources\RetribusiRealizationReportResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\RetribusiRealizationReportResource;
use Illuminate\Routing\Router;


class RetribusiRealizationReportApiService extends ApiService
{
    protected static string | null $resource = RetribusiRealizationReportResource::class;

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
