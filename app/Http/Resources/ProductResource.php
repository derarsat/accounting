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
            'branch' => $this->branch,
            'category_id' => $this->category_id,
            'alert_when_remaining' => $this->alert_when_remaining,
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
