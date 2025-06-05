<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['currency' => 'SYP', 'symbol' => 'ل.س', 'display' => 'symbol', 'exchange_rate' => 9800, 'created_at' => now(), 'updated_at' => now()],
            ['currency' => 'USD', 'symbol' => '$', 'display' => 'symbol', 'exchange_rate' => 1.0, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($currencies as &$currency) {
            $currency['is_default'] = false;
            $currency['created_at'] = now();
            $currency['updated_at'] = now();
        }

        // Set USD as the default currency
        $currencies[0]['is_default'] = true;

        DB::table('currencies')->insert($currencies);
        $this->command->info('currency Settings have been set up successfully.');

    }
}
