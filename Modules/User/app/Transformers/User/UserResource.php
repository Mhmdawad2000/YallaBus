<?php

namespace Modules\User\Transformers\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Contracts\Permission;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "full_name" => $this->full_name,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "code_phone" => $this->code_phone,
            "phone" => $this->phone,
            "city" => $this->when(
                $this->whenLoaded('city'),
                function () {
                    return [
                        'id' => $this->city->id,
                        'name' => $this->city->name,
                    ];
                }
            ),
            "role" => $this->when(
                $this->whenLoaded('role'),
                function () {
                    return [
                        'id' => $this->role->id,
                        'name' => $this->role->changeable_name,
                        'permissions' => $this->whenLoaded('role', function () {
                            return $this->role->permissions->pluck('name');
                        }),
                    ];
                }
            ),
        ];
    }
}
