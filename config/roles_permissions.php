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
        ['name' => 'resolve_complaint', 'changeable_name' => 'Resolve Complaint'],

        //Booking
        ['name' => 'read_all_bookings', 'changeable_name' => 'Show All Bookings'],
        ['name' => 'read_my_bookings', 'changeable_name' => 'Show My Bookings'],
        ['name' => 'change_status_booking', 'changeable_name' => 'Change Status Booking'],
        ['name' => 'create_booking', 'changeable_name' => 'Create Booking'],
        ['name' => 'read_booking', 'changeable_name' => 'Show Booking'],
        ['name' => 'delete_booking', 'changeable_name' => 'Delete Booking'],
        ['name' => 'cancel_booking', 'changeable_name' => 'Cancel Booking'],


        // Bus (CRUD)
        ['name' => 'read_all_buses', 'changeable_name' => 'Show All Buses'],
        ['name' => 'read_bus', 'changeable_name' => 'Show Bus Info'],
        ['name' => 'create_bus', 'changeable_name' => 'Create Bus'],
        ['name' => 'update_bus', 'changeable_name' => 'Update Bus'],
        ['name' => 'delete_bus', 'changeable_name' => 'Delete Bus'],

        // Seat (CRUD)
        ['name' => 'read_all_seats', 'changeable_name' => 'Show All Seats'],
        ['name' => 'read_seat', 'changeable_name' => 'Show Seat Info'],
        ['name' => 'create_seat', 'changeable_name' => 'Create Seat'],
        ['name' => 'update_seat', 'changeable_name' => 'Update Seat'],
        ['name' => 'delete_seat', 'changeable_name' => 'Delete Seat'],

        // Trip (CRUD)
        ['name' => 'read_all_trips', 'changeable_name' => 'Show All Trips'],
        ['name' => 'read_trip', 'changeable_name' => 'Show Trip Info'],
        ['name' => 'create_trip', 'changeable_name' => 'Create Trip'],
        ['name' => 'update_trip', 'changeable_name' => 'Update Trip'],
        ['name' => 'delete_trip', 'changeable_name' => 'Delete Trip'],
        ['name' => 'read_my_trips', 'changeable_name' => 'Show My Trips'],
        // ['name' => 'update_trip_status', 'changeable_name' => 'Update Trip Status'],
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

        // Bus (CRUD)
        'read_all_buses',
        'read_bus',
        'create_bus',
        'update_bus',
        'delete_bus',

        // Seat (CRUD)
        'read_all_seats',
        'read_seat',
        'create_seat',
        'update_seat',
        'delete_seat',

        // Trip (CRUD)
        'read_all_trips',
        'read_trip',
        'create_trip',
        'update_trip',
        'delete_trip',
        // 'update_trip_status',
        'read_my_trips',

        // Booking(RDC)
        'read_all_bookings',
        'read_booking',
        'change_status_booking',
        'delete_booking',
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
        'resolve_complaint'
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

        // Booking(RCCM)
        'read_my_bookings',
        'read_booking',
        'create_booking',
        'cancel_booking',

        // Trip(R)
        'read_all_trips',

    ],


    'default' => [
        // User (Settings)
        'change_password',
        'update_profile',
        'update_contact_info',
        'upload_avatar',
    ],



];
