<?php

namespace Modules\Company\Transformers\Driver;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'license_number' => $this->license_number,
            'photo' => $this->photo ? asset('storage/' . $this->photo) : null,
        ];
    }
}
