<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch currency IDs

        $cities = [
            ['name' => 'دمشق', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'حلب', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'حمص', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'اللاذقية', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'حماة', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'طرطوس', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'دير الزور', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الحسكة', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الرقة', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'السويداء', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'درعا', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'إدلب', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'القنيطرة', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'معرة النعمان', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'جبلة', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'صافيتا', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'تدمر', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'الزبداني', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'دوما', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'حرستا', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'جرمانا', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'مصياف', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'بانياس', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('cities')->insert($cities);
        $this->command->info('cities have been set up successfully.');

    }
}

