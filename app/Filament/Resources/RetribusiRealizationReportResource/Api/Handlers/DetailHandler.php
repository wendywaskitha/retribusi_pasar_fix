<?php

namespace App\Filament\Resources\RetribusiRealizationReportResource\Api\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\SettingResource;
use App\Filament\Resources\RetribusiRealizationReportResource;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = RetribusiRealizationReportResource::class;


    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = static::getEloquentQuery();

        // Apply filtering for kolektor role
        $user = Auth::user();
        if ($user && $user->hasRole('kolektor')) {
            $assignedPasarIds = $user->pasars()->pluck('pasars.id')->toArray();
            $query->whereHas('pedagang', function ($q) use ($assignedPasarIds) {
                $q->whereIn('pasar_id', $assignedPasarIds);
            });
        }

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        $transformer = static::getApiTransformer();

        return new $transformer($query);
    }
}
