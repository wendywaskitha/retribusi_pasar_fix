<?php
namespace App\Filament\Resources\PedagangResource\Api\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\PedagangResource;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = PedagangResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    public function handler(Request $request)
    {
        $model = new (static::getModel());

        // Ensure that the kolektor can only create pedagangs for their assigned pasars
        $user = Auth::user();
        if ($user && $user->hasRole('kolektor')) {
            $assignedPasarIds = $user->pasars()->pluck('pasars.id')->toArray();
            $request->validate([
                'pasar_id' => 'required|in:' . implode(',', $assignedPasarIds),
            ]);
        }

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}
