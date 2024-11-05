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
            $assignedPasarIds = $user->pasars()->pluck('pasars.id')->toArray();

            // Validate that the pasar_id is in the assigned pasars
            if (!in_array($request->input('pasar_id'), $assignedPasarIds)) {
                return static::sendNotFoundResponse('Unauthorized pasar access');
            }
        }

        $model = new (static::getModel());

        $model->fill($request->all());

        $model->user_id = $user->id; // Set the current user as creator

        $model->save();

        // Handle retribusi_pembayaran_items if present in request
        if ($request->has('items')) {
            foreach ($request->input('items') as $item) {
                $model->items()->create($item);
            }
            $model->updateTotal(); // Update the total after creating items
        }

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}
