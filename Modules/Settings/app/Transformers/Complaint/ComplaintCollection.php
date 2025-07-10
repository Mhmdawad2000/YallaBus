<?php

namespace Modules\Settings\Transformers\Complaint;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ComplaintCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => ComplaintResource::collection($this->collection),
        ];
    }

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