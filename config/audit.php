<?php
/*
 * Allows Models To Log The Activity In The ActivityLog Table.
 */
return [
    'allow_models' => [
        // Modules User 
        ['class' => \Modules\User\Models\User::class, 'table' => 'users'],
        // Modules Settings 
        ['class' => \Modules\Settings\Models\City::class, 'table' => 'cities'],
        ['class' => \Modules\Settings\Models\Complaint::class, 'table' => 'complaints'],
        ['class' => \Modules\Settings\Models\Country::class, 'table' => 'countries'],
        ['class' => \Modules\Settings\Models\Currency::class, 'table' => 'currencies'],
        ['class' => \Modules\Settings\Models\Review::class, 'table' => 'reviews'],
        // Modules Payment 
        ['class' => \Modules\Payment\Models\Payment::class, 'table' => 'payments'],
        // Modules Company 
        ['class' => \Modules\Company\Models\Company::class, 'table' => 'companies'],
        ['class' => \Modules\Company\Models\Bus::class, 'table' => 'buses'],
        ['class' => \Modules\Company\Models\Driver::class, 'table' => 'drivers'],
        ['class' => \Modules\Company\Models\Seat::class, 'table' => 'seats'],
        // Modules Booking 
        ['class' => \Modules\Booking\Models\Booking::class, 'table' => 'bookings'],
        ['class' => \Modules\Booking\Models\BookingSeat::class, 'table' => 'booking_seats'],
        // Modules Role 
        ['class' => \Spatie\Permission\Models\Role::class, 'table' => 'roles'],
        ['class' => \Spatie\Permission\Models\Permission::class, 'table' => 'permissions'],
    ],
];
