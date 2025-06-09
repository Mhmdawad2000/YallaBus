<?php

namespace Modules\Settings\Services\Permission;

use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class PermissionService
 *
 * Handles CRUD operations for Permissions and their associated permissions.
 */
class PermissionService implements IPermissionService
{
    /**
     * Get all Permissions with their permissions.
     *
     * @return Collection<Permission>
     */
    public function list(): Collection
    {
        try {
            return Permission::get();
        } catch (Exception $e) {
            Log::error('Failed to get Permissions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update the Permission's name and permissions.
     *
     * @param int $id
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function update(int $id, array $data): array
    {
        try {
            $Permission = Permission::find($id);
            if (!$Permission) {
                Log::error("Permission not found for updating: ID {$id}");
                return [3, []];
            }
            $Permission->update(['changeable_name' => $data['name']]);
            return [1, $Permission];
        } catch (ModelNotFoundException $e) {
            Log::warning("Permission not found for update: ID {$id}");
            throw $e;
        } catch (Exception $e) {
            Log::error('Failed to update Permission: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get a Permission by ID with its permissions.
     *
     * @param int $id
     * @return array
     * @throws ModelNotFoundException
     */
    public function get(int $id): array
    {
        try {
            $Permission = Permission::find($id);
            if (!$Permission) {
                return [3, []];
            }
            return [1, $Permission];
        } catch (Exception $e) {
            Log::error('Failed to get Permission ID=' . $id . ': ' . $e->getMessage());
            throw $e;
        }
    }
}
