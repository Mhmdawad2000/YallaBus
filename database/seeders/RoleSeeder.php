<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = collect(config('roles_permissions.roles'));

        $roles->unique('name')->each(function ($roleData) {
            $existingRole = Role::where('name', $roleData['name'])->first();
            if (!$existingRole) {
                $newRoleData = [
                    'name' => $roleData['name'],
                    'guard_name' => 'web',
                    'changeable_name' => $roleData['changeable_name']
                ];

                if (in_array($roleData['name'], ['super-admin', 'company-admin', 'complaint-reviewer', 'default', 'user'])) {
                    $newRoleData['can_delete'] = false;
                }

                // if ($roleData['name'] === 'super-admin') {
                //     $newRoleData['policy'] = null;
                // } elseif ($roleData['name'] === 'teacher') {
                //     $newRoleData['policy'] = \Modules\Course\Policies\TeacherPolicy::class;
                // } else {
                //     $newRoleData['policy'] = \Modules\Course\Policies\CountryPolicy::class;
                // }

                $roleModel = Role::create($newRoleData);
            } else {
                $roleModel = $existingRole;
            }

            switch ($roleData['name']) {
                case 'super-admin':
                    $permissions = collect(config('roles_permissions.permissions'))
                        ->pluck('name')
                        ->toArray();

                    $roleModel->syncPermissions($permissions);
                    break;
                default:
                    break;
            }
        });

        // Clear the role cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->command->info('The Role setup successfully.');
    }
}
