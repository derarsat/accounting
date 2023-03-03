<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletOperationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "amount" => $this->amount,
            "rate" => $this->rate,
            "currency_was" => $this->currency_was,
            "currency_became" => $this->currency_became,
            "type" => $this->type,
            "model_id" => $this->model_id,
            "description" => $this->description,
            "branch" => $this->branch,
            "currency" => $this->currency,
            "created_at" => $this->created_at,
        ];
    }
}
