<?php
namespace App\Filament\Resources\RetribusiRealizationReportResource\Api\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\RetribusiRealizationReportResource;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = RetribusiRealizationReportResource::class;


    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();

        // Apply filtering for kolektor role
        $user = Auth::user();
        if ($user && $user->hasRole('kolektor')) {
            $assignedPasarIds = $user->pasars()->pluck('pasars.id')->toArray();
            $query->whereHas('pedagang', function ($q) use ($assignedPasarIds) {
                $q->whereIn('pasar_id', $assignedPasarIds);
            });
        }

        $query = QueryBuilder::for($query)
        ->allowedFields($this->getAllowedFields() ?? [])
        ->allowedSorts($this->getAllowedSorts() ?? [])
        ->allowedFilters($this->getAllowedFilters() ?? [])
        ->allowedIncludes($this->getAllowedIncludes() ?? [])
        ->paginate(request()->query('per_page'))
        ->appends(request()->query());

        return static::getApiTransformer()::collection($query);
    }
}
