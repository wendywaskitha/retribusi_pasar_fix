<?php
namespace App\Filament\Resources\RetribusiRealizationReportResource\Api\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\RetribusiRealizationReportResource;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = RetribusiRealizationReportResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    public function handler(Request $request)
    {
        // Check if user is kolektor and validate pasar_id
        $user = Auth::user();
        if ($user && $user->hasRole('kolektor')) {
            $assignedPasarIds = $user->pasars()->pluck('pasars.id')->toArray();

            // Validate that the pasar_id is in the assigned pasars
            if (!in_array($request->input('pasar_id'), $assignedPasarIds)) {
                return static::sendNotFoundResponse('Unauthorized pasar access');
            }
        }

        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}
