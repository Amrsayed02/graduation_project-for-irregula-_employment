<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banners = [
            [
                'image' => 'imagesfp/banner/banner_1.png',
                'arrange' => 1,
                'name' => 'Banner 1',
                'banner_url' => 'https://translate.google.com/',
                'type' => 'banner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'imagesfp/banner/banner_2.png',
                'arrange' => 2,
                'name' => 'Banner 2',
                'type' => 'banner',
                'banner_url' => 'https://translate.google.com/',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        // Insert the data into the table
        DB::table('banners')->insert($banners);
    }
}
