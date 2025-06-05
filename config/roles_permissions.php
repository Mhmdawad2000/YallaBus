<?php

return [
    'permissions' => [
        // Role (CRUD)
        ['name' => 'read_all_roles', 'changeable_name' => 'Show All Roles'],
        ['name' => 'read_role', 'changeable_name' => 'Show Role Info'],
        ['name' => 'create_role', 'changeable_name' => 'Create Role'],
        ['name' => 'update_role', 'changeable_name' => 'Update Role'],
        ['name' => 'delete_role', 'changeable_name' => 'Delete Role'],

        // Permission (RU)
        ['name' => 'read_all_permissions', 'changeable_name' => 'Show All Permissions'],
        ['name' => 'read_permission', 'changeable_name' => 'Show Permission Info'],
        ['name' => 'update_permission', 'changeable_name' => 'Update Permission'],

        // User (CRUD)
        ['name' => 'read_all_users', 'changeable_name' => 'Show All Users'],
        ['name' => 'read_user', 'changeable_name' => 'Show User Info'],
        ['name' => 'create_user', 'changeable_name' => 'Create User'],
        ['name' => 'update_user', 'changeable_name' => 'Update User'],
        ['name' => 'delete_user', 'changeable_name' => 'Delete User'],

    ],
    'roles' => [
        ['name' => 'super-admin', 'changeable_name' => 'Super Admin'],
        ['name' => 'company-admin', 'changeable_name' => 'Company Admin'],
        ['name' => 'complaint-reviewer', 'changeable_name' => 'Complaint Reviewer'],
        ['name' => 'user', 'changeable_name' => 'User'],
        ['name' => 'default', 'changeable_name' => 'Default'],
    ],

];