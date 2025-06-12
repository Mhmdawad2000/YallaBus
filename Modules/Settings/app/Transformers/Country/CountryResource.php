<?php
namespace Modules\Settings\Transformers\Country;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'dialing_code' => $this->dialing_code,
            'currency' => $this->when(
                $this->whenLoaded('currency'),
                function () {
                    return [
                        'id' => $this->currency->id ?? null,
                        'name' => $this->currency->currency ?? null,
                        'symbol' => $this->currency->symbol ?? null,
                    ];
                }
            ),
        ];
    }
}
