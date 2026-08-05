<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "value" => $this->value,
            "payer" => new UserResource($this->whenLoaded('payer')),
            "payee" => new UserResource($this->whenLoaded('payee')),
            "created_at" => $this->created_at,
        ];
    }
}
