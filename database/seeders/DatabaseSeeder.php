<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Settings\Database\Seeders\CitySeeder;
use Modules\Settings\Database\Seeders\CountrySeeder;
use Modules\Settings\Database\Seeders\CurrencySeeder;
use Modules\User\database\seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            CurrencySeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            UserSeeder::class,
        ]);
    }
}
