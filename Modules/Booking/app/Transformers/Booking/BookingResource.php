<?php

namespace Modules\Booking\Transformers\Booking;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'trip' => [
                'id' => $this->trip->id,
                'name' => $this->trip->name,
            ],
            'booking_reference' => $this->booking_reference,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'cancelled_at' => $this->cancelled_at?->format('Y-m-d H:i:s'),
            'cancellation_reason' => $this->cancellation_reason,
        ];
    }
}