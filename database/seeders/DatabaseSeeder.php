<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(EmdEmailSettingSeeder::class);
        $this->call(EmdCountrySeeder::class);
        $this->call(EmdPermissionSeeder::class);
        $this->call(EmdCustomFieldSeeder::class);
    }
}
