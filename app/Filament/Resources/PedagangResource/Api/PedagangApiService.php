<?php
namespace App\Filament\Resources\PedagangResource\Api;

use Illuminate\Routing\Router;
use Rupadana\ApiService\ApiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PedagangResource;


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
