<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServicesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $services = [
            [
                'name' => 'Academic Advising',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Tutoring',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Career Counseling',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Idempotent: no duplicates when you re-seed
        DB::table('services')->upsert(
            $services,
            ['name'],                // unique key
            ['is_active', 'updated_at'] // fields to update if exists
        );
    }
}
