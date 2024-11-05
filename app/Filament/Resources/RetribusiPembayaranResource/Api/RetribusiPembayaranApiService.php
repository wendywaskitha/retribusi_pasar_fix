<?php
namespace App\Filament\Resources\RetribusiPembayaranResource\Api;

use Illuminate\Routing\Router;
use Rupadana\ApiService\ApiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RetribusiPembayaranResource;


class RetribusiPembayaranApiService extends ApiService
{
    protected static string | null $resource = RetribusiPembayaranResource::class;

    public function getTableQuery(): Builder
    {
        $query = static::getResource()::getEloquentQuery();

        if (Auth::user()->hasRole('kolektor')) {
            $assignedPasarIds = Auth::user()->pasars->pluck('id');
            $query->whereIn('pasar_id', $assignedPasarIds);
        }

        return $query;
    }

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
