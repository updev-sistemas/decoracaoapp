<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'warehouse_id' => $this->warehouse_id,
            'sequence'     => $this->sequence,
            'layover'      => $this->layover,

            // Carrega o armazém associado se o relacionamento estiver carregado (Eager Loading)
            'warehouse'    => new WarehouseResource($this->whenLoaded('warehouse')),

            'created_at'   => $this->created_at?->toIso8601String(),
            'updated_at'   => $this->updated_at?->toIso8601String(),
        ];
    }
}
