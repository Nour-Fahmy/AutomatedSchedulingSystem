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

        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@alignup.local',
                'password' => Hash::make('12345678'),
                'type' => 'admin',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Faculty User',
                'email' => 'faculty@alignup.local',
                'password' => Hash::make('12345678'),
                'type' => 'faculty',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Student User',
                'email' => 'student@alignup.local',
                'password' => Hash::make('12345678'),
                'type' => 'student',
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        /**
         * Idempotent insert/update:
         * - If email exists → update user
         * - If not → insert user
         */
        DB::table('users')->upsert(
            $users,
            ['email'], // unique key
            ['name', 'password', 'type', 'email_verified_at', 'updated_at']
        );
    }
}
