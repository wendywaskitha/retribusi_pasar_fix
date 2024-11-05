<?php
namespace App\Filament\Resources\RetribusiRealizationReportResource\Api;

use Illuminate\Routing\Router;
use Rupadana\ApiService\ApiService;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\RetribusiRealizationReportResource;


class RetribusiRealizationReportApiService extends ApiService
{
    protected static string | null $resource = RetribusiRealizationReportResource::class;

    public function getTableQuery(): Builder
    {
        $query = static::getResource()::getEloquentQuery();

        if (Auth::user()->hasRole('kolektor')) {
            $assignedPasarIds = Auth::user()->pasars->pluck('id');
            $query->whereHas('pedagang', function ($q) use ($assignedPasarIds) {
                $q->whereIn('pasar_id', $assignedPasarIds);
            });
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
