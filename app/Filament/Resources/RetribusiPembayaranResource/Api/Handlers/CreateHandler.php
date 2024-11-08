<?php
namespace App\Filament\Resources\RetribusiPembayaranResource\Api\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\RetribusiPembayaranResource;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = RetribusiPembayaranResource::class;

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
            $assignedPasarIds = $user->pasars()->pluck('id')->toArray();

            // Validate that the pasar_id is in the assigned pasars
            $request->validate([
                'pasar_id' => 'required|in:' . implode(',', $assignedPasarIds)
            ], [
                'pasar_id.in' => 'You are not authorized to create a payment for this market.'
            ]);
        }

        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}
