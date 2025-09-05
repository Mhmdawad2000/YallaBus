<?php

namespace Modules\User\Transformers\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBalanceLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'old_balance' => $this->old_balance,
            'new_balance' => $this->new_balance,
            'transaction_type' => $this->transaction_type,
            'reason' => $this->reason,
            'user' => $this->when(
                $this->whenLoaded('user'),
                function () {
                    return [
                        'id' => $this->user->id,
                        'full_name' => $this->user->full_name,
                        'email' => $this->user->email,
                    ];
                }
            ),
            'booking' => $this->when(
                $this->whenLoaded('booking'),
                function () {
                    return [
                        'id' => $this->booking->id,
                        'trip_id' => $this->booking->trip_id,
                        'total_amount' => $this->booking->total_amount,
                    ];
                }
            ),
        ];
    }
}