<?php

namespace Modules\Settings\Transformers\City;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_count' => $this->user_count,
            'country' => $this->when(
                $this->whenLoaded('country'),
                function () {
                    return [
                        'id' => $this->country->id ?? null,
                        'name' => $this->country->name ?? null,
                    ];
                }
            ),
        ];
    }
}
