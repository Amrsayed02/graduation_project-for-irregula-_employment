<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            PermissionTableSeeder::class,
            RoleUserSeeder::class,
            CountryTableSeeder::class,
            CitySeeder::class,
            BannerTableSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            FaqsTableSeeder::class,
            SettingWebTableSeeder::class,
            SettingsTableSeeder::class,
        ]);
    }
}
