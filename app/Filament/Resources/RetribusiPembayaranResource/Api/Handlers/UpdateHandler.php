<?php
namespace App\Filament\Resources\RetribusiPembayaranResource\Api\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\RetribusiPembayaranResource;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = RetribusiPembayaranResource::class;

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
            $query->whereIn('pasar_id', $assignedPasarIds);
        }

        if (!$model) return static::sendNotFoundResponse();

        // Validate pasar_id if it's being updated
        if ($request->has('pasar_id') && $user && $user->hasRole('kolektor')) {
            $assignedPasarIds = $user->pasars()->pluck('pasars.id')->toArray();
            if (!in_array($request->input('pasar_id'), $assignedPasarIds)) {
                return static::sendNotFoundResponse('Unauthorized pasar access');
            }
        }

        $model->fill($request->all());

        $model->save();

        //  Handle retribusi_pembayaran_items if present in request
        if ($request->has('items')) {
            // Remove existing items
            $model->items()->delete();

            // Add new items
            foreach ($request->input('items') as $item) {
                $model->items()->create($item);
            }
            $model->updateTotal(); // Update the total after updating items
        }

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}
