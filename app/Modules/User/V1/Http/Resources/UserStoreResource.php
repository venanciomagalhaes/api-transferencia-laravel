<?php

namespace App\Modules\User\V1\Http\Resources;

use App\Modules\Permissions\V1\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'type' => $this->type->name,
            'document' => $this->cpf_cnpj,
            'email' => $this->email,
            'amount' => $this->wallet->amount,
            'permissions' => $this->type?->permissions->map(function (Permission $permission) {
                return [
                    'name' => $permission->name,
                    'description' => $permission?->description,
                ];
            })->toArray(),
        ];
    }
}
