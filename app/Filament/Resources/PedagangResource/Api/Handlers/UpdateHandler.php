<?php
namespace App\Filament\Resources\PedagangResource\Api\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\PedagangResource;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = PedagangResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
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
            $assignedPasarIds = $user->pasars()->pluck('pasars.id')->toArray();
            $model->whereIn('pasar_id', $assignedPasarIds);
        }

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());


        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}
