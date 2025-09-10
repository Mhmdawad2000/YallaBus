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
            'user' => $this->user,
            'trip' => $this->trip,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at
        ];
    }
}
