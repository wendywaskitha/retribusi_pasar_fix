<?php
namespace App\Filament\Resources\RetribusiPembayaranResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RetribusiPembayaranTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Get the base data
        $data = parent::toArray($request);

        // Add pedagang details
        $data['pedagang'] = $this->pedagang ? [
            'id' => $this->pedagang->id,
            'name' => $this->pedagang->name,
            'nik' => $this->pedagang->nik,
            'pasar_name' => $this->pedagang->pasar ? $this->pedagang->pasar->name : null,
        ] : null;

        // Add pasar details
        $data['pasar'] = $this->pasar ? [
            'id' => $this->pasar->id,
            'name' => $this->pasar->name,
        ] : null;

        // Add user details who created the record
        $data['user'] = $this->user ? [
            'id' => $this->user->id,
            'name' => $this->user->name,
        ] : null;

        // Include items if available
        if ($this->items && $this->items->count() > 0) {
            $data['items'] = $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'retribusi_name' => $item->retribusi ? $item->retribusi->name : null,
                    'biaya' => $item->biaya,
                ];
            });
        }

        return $data;
    }
}
