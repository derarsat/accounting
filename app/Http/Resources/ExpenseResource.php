<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "total" => $this->total,
            "branch" => new BranchResource($this->branch),
            "branch_id" => $this->branch_id,
            "updated_at" => $this->updated_at
        ];
    }
}
