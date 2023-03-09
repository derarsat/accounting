<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            "amount" => $this->amount,
            "id" => $this->id,
            "rate" => $this->rate,
            "type" => $this->type,
            "currency_was" => $this->currency_was,
            "currency_became" => $this->currency_became,
            "current_account_was" => $this->current_account_was,
            "current_account_became" => $this->current_account_became,
            'branch' => $this->branch,
            'currency' => $this->currency,
            'trader' => $this->trader,
            'created_at' => $this->created_at,
        ];
    }
}
