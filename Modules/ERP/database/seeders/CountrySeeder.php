<?php

namespace Modules\ERP\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        // Path to your JSON file
        $path = database_path('data/kenya_locations_with_towns.json');
        
        if (!File::exists($path)) {
            $this->command->error("JSON file not found at {$path}");
            return;
        }

        $data = json_decode(File::get($path), true);

        DB::transaction(function () use ($data) {
            // 1. Seed Country
            $countryData = $data['countries'][0];
            $country = Country::updateOrCreate(
                ['id' => $countryData['id']],
                ['name' => $countryData['name'], 'code' => $countryData['code']]
            );

            // 2. Seed Counties
            foreach ($data['counties'] as $c) {
                County::updateOrCreate(
                    ['id' => $c['id']],
                    [
                        'country_id' => $country->id,
                        'name' => $c['name'],
                        'code' => $c['code']
                    ]
                );
            }

            // 3. Seed Sub-Counties
            foreach ($data['sub_counties'] as $sc) {
                SubCounty::updateOrCreate(
                    ['id' => $sc['id']],
                    [
                        'county_id' => $sc['county_id'],
                        'name' => $sc['name']
                    ]
                );
            }

            // 4. Seed Wards (Bulk Insert for performance)
            $this->command->info('Seeding Wards...');
            foreach (array_chunk($data['wards'], 500) as $chunk) {
                Ward::insert($chunk);
            }

            // 5. Seed Towns
            $this->command->info('Seeding Towns...');
            foreach ($data['towns'] as $t) {
                Town::updateOrCreate(
                    ['id' => $t['id']],
                    [
                        'county_id' => $t['county_id'],
                        'name' => $t['name'],
                        'postal_code' => $t['postal_code'] ?? null
                    ]
                );
            }
        });

        $this->command->info('Kenya Locations with Towns seeded successfully!');
    }
}
