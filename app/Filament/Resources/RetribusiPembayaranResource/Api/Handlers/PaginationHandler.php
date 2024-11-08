<?php
namespace App\Filament\Resources\RetribusiPembayaranResource\Api\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\RetribusiPembayaranResource;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = RetribusiPembayaranResource::class;


    public function handler()
    {
        $query = static::getEloquentQuery();
        $model = static::getModel();

        // Apply filtering for kolektor role
        $user = Auth::user();
        if ($user && $user->hasRole('kolektor')) {
            // Get assigned pasar IDs
            $assignedPasarIds = $user->pasars()->pluck('pasars.id')->toArray(); // Use 'pasars.id' to avoid ambiguity
            $query->whereIn('pasar_id', $assignedPasarIds);
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
