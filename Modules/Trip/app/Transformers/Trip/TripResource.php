<?php

namespace Modules\Trip\Transformers\Trip;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company' => [
                'id' => $this->company->id,
                'name' => $this->company->name,
            ],
            'bus' => [
                'id' => $this->bus->id,
                'plate_number' => $this->bus->plate_number,
                'model' => $this->bus->model,
                'seats' => $this->bus->seats->map(function ($seat) {
                    return [
                        'id' => $seat->id,
                        'number' => $seat->seat_number,
                        'status' => $seat->is_available, // مثلاً متاح / محجوز
                    ];
                }),
            ],
            'driver' => [
                'id' => $this->driver->id,
                'name' => $this->driver->name,
            ],
            'departure_city' => [
                'id' => $this->departureCity->id,
                'name' => $this->departureCity->name,
            ],
            'arrival_city' => [
                'id' => $this->arrivalCity->id,
                'name' => $this->arrivalCity->name,
            ],
            'departure_time' => $this->departure_time->format('Y-m-d H:i:s'),
            'arrival_time' => $this->arrival_time->format('Y-m-d H:i:s'),
            'price' => $this->price,
            'available_seats' => $this->available_seats,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
