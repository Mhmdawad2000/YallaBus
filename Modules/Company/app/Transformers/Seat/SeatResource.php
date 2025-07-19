<?php

namespace Modules\Company\Transformers\Seat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bus_id' => $this->bus_id,
            'seat_number' => $this->seat_number,
            'class' => $this->class,
            'is_available' => $this->is_available,
        ];
    }
}