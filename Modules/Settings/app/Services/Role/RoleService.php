<?php

namespace Modules\Settings\Services\Role;

use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class RoleService
 *
 * Handles CRUD operations for roles and their associated permissions.
 */
class RoleService implements IRoleService
{
    /**
     * Get all roles with their permissions.
     *
     * @return Collection<Role>
     */
    public function list(): Collection
    {
        try {
            return Role::get();
        } catch (Exception $e) {
            Log::error('Failed to get roles: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new role and assign permissions to it.
     *
     * @param array $data
     * @return Role
     * @throws Exception
     */
    public function create(array $data): Role
    {
        try {
            $role = Role::create(['guard_name' => 'web', 'changeable_name' => $data['changeable_name'], 'name' => strtolower(str_replace(' ', '-', $data['changeable_name'])), 'policy' => config('role_policy.policy.' . $data['policy']), 'can_delete' => $data['can_delete']]);
            $role->syncPermissions($data['permissions']);
            $role->load('permissions');
            return $role;
        } catch (Exception $e) {
            Log::error('Failed to create role: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update the role's name and permissions.
     *
     * @param int $id
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function update(int $id, array $data): array
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                Log::error("Role not found for updating: ID {$id}");
                return [3, []];
            } else if ($role->name == 'super-admin') {
                Log::warning("Role 'Super Admin' can't update");
                return [2, []];
            } else if ($role->name == 'default') {
                $data['name'] = 'default';
            }
            if (isset($data['changeable_name'])) {
                $data['name'] = strtolower(str_replace(' ', '-', $data['changeable_name']));
            }
            if (isset($data['policy'])) {
                $data['policy'] = config('role_policy.policy.' . $data['policy']);
            }
            $role->update($data);
            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }
            $role->load('permissions');
            return [1, $role];
        } catch (ModelNotFoundException $e) {
            Log::warning("Role not found for update: ID {$id}");
            throw $e;
        } catch (Exception $e) {
            Log::error('Failed to update role: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a role by ID.
     *
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function delete(int $id): array
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                Log::error("Role not found for deletion: ID {$id}");
                return [3, 'الدور المراد حذفه غير موجود'];
            } else if (!$role->can_delete) {
                Log::warning("Role '" . $role->name . "' can't delete");
                return [2, "الدور '" . $role->name . "' لا يمكن حذفه"];
            }
            DB::transaction(function () use ($role) {
                $defaultRole = Role::where('name', 'default')->firstOrFail();
                $users = $role->users()->get();
                foreach ($users as $user) {
                    $user->role_id = $defaultRole->id;
                    $user->save();
                    $user->removeRole($role);
                    $user->assignRole($defaultRole);
                }
                $role->delete();
            });
            return [1, 201, 'Role delete successfully'];
        } catch (ModelNotFoundException $e) {
            Log::warning("Role not found for deletion: ID {$id}");
            throw $e;
        } catch (Exception $e) {
            Log::error('Failed to delete role: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get a role by ID with its permissions.
     *
     * @param int $id
     * @return array
     * @throws ModelNotFoundException
     */
    public function get(int $id): array
    {
        try {
            $role = Role::with('permissions')->find($id);
            if (!$role) {
                return [3, []];
            }
            return [1, $role];
        } catch (Exception $e) {
            Log::error('Failed to get role ID=' . $id . ': ' . $e->getMessage());
            throw $e;
        }
    }
}
