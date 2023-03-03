<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "quantity_value" => $this->quantity_value,
            "purchased" => $this->purchased,
            "barcode" => $this->barcode,
            "price" => $this->price,
            "expire" => $this->expire,
            "weight_value" => $this->weight_value,
            "quantity" => new QuantityResource($this->quantity),
            "trader" => new TraderResource($this->trader),
        ];
    }
}
