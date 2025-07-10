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

        // User (Settings)
        ['name' => 'change_password', 'changeable_name' => 'User Change Password'],
        ['name' => 'update_profile', 'changeable_name' => 'User Update Profile'],
        ['name' => 'update_contact_info', 'changeable_name' => 'User Update Contact Info'],
        ['name' => 'upload_avatar', 'changeable_name' => 'User Upload Avatar'],

        // Driver (CRUD)
        ['name' => 'read_all_drivers', 'changeable_name' => 'Show All Drivers'],
        ['name' => 'read_driver', 'changeable_name' => 'Show Driver Info'],
        ['name' => 'create_driver', 'changeable_name' => 'Create Driver'],
        ['name' => 'update_driver', 'changeable_name' => 'Update Driver'],
        ['name' => 'delete_driver', 'changeable_name' => 'Delete Driver'],

        // Review (CRUD)
        ['name' => 'read_all_reviews', 'changeable_name' => 'Show All Reviews'],
        ['name' => 'read_review', 'changeable_name' => 'Show Review Info'],
        ['name' => 'create_review', 'changeable_name' => 'Create Review'],

        // Complaint (CRUD)
        ['name' => 'read_all_complaints', 'changeable_name' => 'Show All Complaints'],
        ['name' => 'read_complaint', 'changeable_name' => 'Show Complaint Info'],
        ['name' => 'create_complaint', 'changeable_name' => 'Create Complaint'],
        ['name' => 'delete_complaint', 'changeable_name' => 'Delete Complaint'],

    ],
    'roles' => [
        ['name' => 'super-admin', 'changeable_name' => 'Super Admin'],
        ['name' => 'company-admin', 'changeable_name' => 'Company Admin'],
        ['name' => 'complaint-reviewer', 'changeable_name' => 'Complaint Reviewer'],
        ['name' => 'user', 'changeable_name' => 'User'],
        ['name' => 'default', 'changeable_name' => 'Default'],
    ],



    'super-admin' => [

        // Role (CRUD)
        'read_all_roles',
        'read_role',
        'create_role',
        'update_role',
        'delete_role',

        // Permission (RU)
        'read_all_permissions',
        'read_permission',
        'update_permission',

        // User (CRUD)
        'read_all_users',
        'read_user',
        'create_user',
        'update_user',
        'delete_user',

        // Currency (TCRUD)
        'toggle_default_currency',
        'read_default_currency',
        'create_currency',
        'update_currency',
        'delete_currency',

        // Country (CUD)
        'create_country',
        'update_country',
        'delete_country',

        // City (CUD)
        'create_city',
        'update_city',
        'delete_city',

        // User (Settings)
        'change_password',
        'update_profile',
        'update_contact_info',
        'upload_avatar',

        // Review (CRUD)
        'read_all_reviews',
        'read_review',

        // Complaint (CRUD)
        'read_all_complaints',
        'read_complaint',
        'delete_complaint',

    ],




    'company-admin' => [
        // User (Settings)
        'change_password',
        'update_profile',
        'update_contact_info',
        'upload_avatar',

        // Driver (CRUD)
        'read_all_drivers',
        'read_driver',
        'create_driver',
        'update_driver',
        'delete_driver',
    ],




    'complaint-reviewer' => [

        // User (Settings)
        'change_password',
        'update_profile',
        'update_contact_info',
        'upload_avatar',

        // Review (CRUD)
        'read_all_reviews',
        'read_review',

        // Complaint (CRUD)
        'read_all_complaints',
        'read_complaint',
    ],



    'user' => [

        // User (Settings)
        'change_password',
        'update_profile',
        'update_contact_info',
        'upload_avatar',

        // Review (CRUD)
        'create_review',

        // Complaint (CRUD)
        'read_complaint',
        'create_complaint',
        'delete_complaint',
    ],


    'default' => [

        // User (Settings)
        'change_password',
        'update_profile',
        'update_contact_info',
        'upload_avatar',
    ],



];