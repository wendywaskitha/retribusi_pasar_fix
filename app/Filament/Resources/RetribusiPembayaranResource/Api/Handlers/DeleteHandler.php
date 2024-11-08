<?php
namespace App\Filament\Resources\RetribusiPembayaranResource\Api\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\RetribusiPembayaranResource;

class DeleteHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = RetribusiPembayaranResource::class;

    public static function getMethod()
    {
        return Handlers::DELETE;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    public function handler(Request $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        // Apply filtering for kolektor role
        $user = Auth::user();
        if ($user && $user->hasRole('kolektor')) {
            $assignedPasarIds = $user->pasars()->pluck('id')->toArray();

            // Check if the model belongs to an assigned pasar
            if (!in_array($model->pasar_id, $assignedPasarIds)) {
                return static::sendNotFoundResponse('Unauthorized access');
            }
        }

        if (!$model) return static::sendNotFoundResponse();

        $model->delete();

        return static::sendSuccessResponse($model, "Successfully Delete Resource");
    }
}
