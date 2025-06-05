<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch currency IDs
        $currenciesMap = DB::table('currencies')->pluck('id', 'currency');

        $countries = [
            ['name' => 'سوريا', 'dialing_code' => '+963', 'currency' => 'SYP', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($countries as &$country) {
            $country['currency_id'] = $currenciesMap[$country['currency']];
            unset($country['currency']); // Remove temporary key
        }

        DB::table('countries')->insert($countries);
        $this->command->info('Countries have been set up successfully.');

    }
}

