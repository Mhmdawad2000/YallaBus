<?php

namespace Modules\Company\Transformers\Bus;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'plate_number' => $this->plate_number,
            'model' => $this->model,
            'capacity' => $this->capacity,
            'type' => $this->type,
            'amenities' => $this->amenities,
        ];
    }
}