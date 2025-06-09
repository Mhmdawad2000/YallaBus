<?php

namespace Modules\Settings\Transformers\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Settings\Responce\Permission\PermissionResponce;

class RoleResource extends JsonResource
{
    private $policies;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */

    public function toArray(Request $request): array
    {
        $this->policies = config('role_policy.policy');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'changeable_name' => $this->changeable_name,
            'policy' => array_search($this->policy, $this->policies, true),
            'can_delete' => (bool) $this->can_delete,
            'permissions' => $this->whenLoaded('permissions', function () {
                return (new PermissionResponce($this->permissions->all()))->data;
            }),
        ];
    }
}

