<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            [1, 1, 'القاهرة', 41.0082, 28.9784, '2023-09-09 13:27:57'],
            [2, 1, 'المعادي', 39.9334, 32.8597, '2023-09-09 13:27:57'],
            [3, 1, 'الإسكندرية', 38.4192, 27.1287, '2023-09-09 13:27:57'],
            [4, 1, 'طنطا ', 40.1826, 29.0668, '2023-09-09 13:27:57'],
            [5, 1, 'السويس', 40.8533, 29.8811, '2023-09-09 13:27:57'],
            [6, 1, 'شرم الشيخ', 36.8841, 30.7056, '2023-09-09 13:27:57'],
            [7, 1, 'المنيا', 37.8746, 32.4846, '2023-09-09 13:27:57'],
            [8, 1, 'اسوان', 36.2021, 36.1699, '2023-09-09 13:27:57'],
            [9, 1, 'كفر الشيخ ', 39.6470, 27.8827, '2023-09-09 13:27:57'],
            [10, 1, 'الجيزة', 40.7640, 30.4400, '2023-09-09 13:27:57'],
        ];
        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'id' => $city[0],
                'country_id' => $city[1],
                'name' => $city[2],
            ]);
        }
    }
}
