<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = collect(config('roles_permissions.permissions'));

        $permissions->unique('name')->each(function ($permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                [
                    'changeable_name' => $permission['changeable_name'],
                    'guard_name' => 'web',
                ]
            );
        });

        // Clear the permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->command->info('The Permissions setup successfully.');
    }
}