<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'الكهرباء',
                'color' => '0xffE7F1FE',
                'image' => ('imagesfp/category/Elektrik.png'),
            ],
            [
                'title' => 'السباكة',
                'color' => '0xffE7F1FE',
                'image' => ('imagesfp/category/Tesisat.png'),
            ],
            [
                'title' => 'الدهانات',
                'color' => '0xffE7F1FE',
                'image' => ('imagesfp/category/Boyalar.png'),
            ],
        ];

        // Insert data into the categories table
        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }
    }
}
