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

        // Currency (TCRUD)
        ['name' => 'toggle_default_currency', 'changeable_name' => 'Toggle Default currency'],
        ['name' => 'read_default_currency', 'changeable_name' => 'Show Default currency'],
        ['name' => 'create_currency', 'changeable_name' => 'Create currency'],
        ['name' => 'update_currency', 'changeable_name' => 'Update currency'],
        ['name' => 'delete_currency', 'changeable_name' => 'Delete currency'],

        // Country (CUD)
        ['name' => 'create_country', 'changeable_name' => 'Create Country'],
        ['name' => 'update_country', 'changeable_name' => 'Update Country'],
        ['name' => 'delete_country', 'changeable_name' => 'Delete Country'],

        // City (CUD)
        ['name' => 'create_city', 'changeable_name' => 'Create City'],
        ['name' => 'update_city', 'changeable_name' => 'Update City'],
        ['name' => 'delete_city', 'changeable_name' => 'Delete City'],

    ],
    'roles' => [
        ['name' => 'super-admin', 'changeable_name' => 'Super Admin'],
        ['name' => 'company-admin', 'changeable_name' => 'Company Admin'],
        ['name' => 'complaint-reviewer', 'changeable_name' => 'Complaint Reviewer'],
        ['name' => 'user', 'changeable_name' => 'User'],
        ['name' => 'default', 'changeable_name' => 'Default'],
    ],

];