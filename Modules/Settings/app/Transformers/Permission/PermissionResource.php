<?php

namespace Modules\Settings\Transformers\Permission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        // Handle direct card data (not nested in 'card' property)
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'changeable_name' => $this->resource->changeable_name,
        ];

    }
}

