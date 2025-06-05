<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users (optional)
        // User::truncate();

        // Get roles
        $superAdminRole = Role::where('name', 'super-admin')->first();

        // Create Super Admin
        $superAdmin = User::create([
            'first_name' => 'System',
            'last_name' => 'Admin',
            'email' => 'admin@yallabus.com',
            'password' => Hash::make('123456uQ!'),
            'code_phone' => '+963',
            'phone' => '987654321',
            'role_id' => $superAdminRole->id,
            'city_id' => 1,
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole($superAdminRole);

        // Create Regular Admin

        $this->command->info('Users seeded successfully!');
        $this->command->table(
            ['Name', 'Email', 'Role', 'City'],
            User::with('roles')->get()->map(function ($user) {
                return [
                    'name' => $user->full_name,
                    'email' => $user->email,
                    'role' => $user->roles->first()->name,
                    'city' => $user->city->name,
                ];
            })->toArray()
        );
    }
}