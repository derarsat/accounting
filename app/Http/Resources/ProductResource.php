<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'branch_id' => $this->branch_id,
            'category_id' => $this->category_id,
            'id' => $this->id,
            'material' => $this->material,
            'location' => $this->location,
            'weight' => $this->weight,
            'name' => $this->name,
            'total_earnings' => $this->total_earnings,
            'total_pieces_sold' => $this->total_pieces_sold,
            'variants' => ProductVariantResource::collection($this->variants),
        ];
    }
}
