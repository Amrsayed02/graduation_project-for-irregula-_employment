<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingWebTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting_webs')->insert([
            'about_us' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ...',
            'terms' => 'Terms in Arabic...',
            'privacy' => 'Privacy Policy in English...',
        ]);
    }
}
