<?php
namespace App\Filament\Resources\PedagangResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PedagangTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        // Add pasar name to the response
        $data['pasar_name'] = $this->resource->pasar ? $this->resource->pasar->name : null;

        return $data;
    }
}
