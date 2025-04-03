<?php

namespace App\Http\Resources\V1;

use App\Helpers\HateoasBuilderHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->resource->uuid,
            'name' => $this->resource->name,
            'role' => $this->resource->role->name,
            'email' => $this->resource->email,
            'cpf_cnpj' => $this->resource->cpf_cnpj,
            'permissions' => $this->resource->role->permissions->map(function ($permission) {
                return [
                    'name' => $permission->name,
                    'description' => $permission->description,
                ];
            }),
            "__links" => (
                new HateoasBuilderHelper(
                    resource: $this->resource,
                    versionApi: 'v1',
                    basePath: '/users'
                )
            )
            ->self()
            ->index()
            ->build()
        ];
    }
}
