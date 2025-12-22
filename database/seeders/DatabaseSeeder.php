<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            ServicesTableSeeder::class,
            SettingsTableSeeder::class,
            AvailabilityRulesSeeder::class,
            ForumSeeder::class,
        ]);
    }
}
