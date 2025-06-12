<?php

namespace Modules\Settings\Transformers\Currency;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'currency' => $this->currency,
            'symbol' => $this->symbol,
            'display' => $this->display,
            'is_default' => $this->is_default,
            'exchange_rate' => $this->exchange_rate,
        ];
    }
}
