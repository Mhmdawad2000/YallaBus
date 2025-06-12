<?php

namespace Modules\Settings\Transformers\Currency;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CurrencyCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => CurrencyResource::collection($this->collection),
        ];
    }
}
