<?php

namespace Modules\Company\Transformers\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DriverCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => DriverResource::collection($this->collection),
        ];
    }

    /**
     * Add pagination meta data.
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
            ]
        ];
    }
}
