<?php

namespace Modules\Settings\Transformers\City;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CityCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return CityResource::collection($this->collection)->all();

    }
}
