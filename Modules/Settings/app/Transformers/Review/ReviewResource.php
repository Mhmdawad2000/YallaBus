<?php

namespace Modules\Settings\Transformers\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'rating' => $this->rating,
            'comment' => $this->comment,
        ];
    }
}