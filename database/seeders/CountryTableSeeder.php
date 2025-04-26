<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            [
                "id" => 1,
                'name' => 'مصر',
                'image' => 'https://app.automark.site/upload/flags/Flag-of-Turkey.png',
                'latitude' => 38.963745,
                'longitude' => 35.243322,
                'code' => '+90',
                'symbol' => 'الجنيه',
                'exchange_rate' => 1,
            ],
        ];

        // Insert data into the countries_admins table
        foreach ($countries as $country) {
            DB::table('countries')->insert($country);
        }
    }
}
