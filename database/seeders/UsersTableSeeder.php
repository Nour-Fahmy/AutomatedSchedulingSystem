<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Admin
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@alignup.local',
            'email_verified_at' => $now,
            'type' => 'admin',
            'password' => Hash::make('password'), // change in production
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Faculty
        DB::table('users')->insert([
            [
                'name' => 'Dr. Sara Faculty',
                'email' => 'sara.faculty@alignup.local',
                'email_verified_at' => $now,
                'type' => 'faculty',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Mr. Omar Faculty',
                'email' => 'omar.faculty@alignup.local',
                'email_verified_at' => $now,
                'type' => 'faculty',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Students
        DB::table('users')->insert([
            [
                'name' => 'Nour Student',
                'email' => 'nour.student@alignup.local',
                'email_verified_at' => $now,
                'type' => 'student',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Mina Student',
                'email' => 'mina.student@alignup.local',
                'email_verified_at' => $now,
                'type' => 'student',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Rana Student',
                'email' => 'rana.student@alignup.local',
                'email_verified_at' => $now,
                'type' => 'student',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
