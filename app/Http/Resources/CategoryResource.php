<?php

namespace App\Http\Resources;

use App\Http\Resources\SectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,

            'section'     => new SectionResource($this->whenLoaded('section')),
            'items'       => ItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
