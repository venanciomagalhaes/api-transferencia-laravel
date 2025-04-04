<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'payer' => $this->resource->payer->name,
            'payee' => $this->resource->payee->name,
            'amount' => $this->resource->amount,
        ];
    }
}
