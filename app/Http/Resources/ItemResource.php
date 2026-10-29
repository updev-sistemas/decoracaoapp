<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'description'        => $this->description,
            'photo_url'          => asset('storage/items/' . $this->photo_ref),
            'original_price'     => (float) $this->original_price,
            'markup'             => $this->markup,
            // Exemplo de campo calculado/formatado
            'final_price'        => round($this->original_price * (1 + ($this->markup / 100)), 2),
            'available_for_rent' => (bool) $this->available_for_rent,

            // Só inclui a categoria se o relacionamento tiver sido carregado (Eager Loading)
            'category'           => new CategoryResource($this->whenLoaded('category')),

            'created_at'         => $this->created_at?->toIso8601String(),
        ];
    }
}
