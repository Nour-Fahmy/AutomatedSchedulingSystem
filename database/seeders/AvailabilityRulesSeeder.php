<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AvailabilityRulesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $facultySara = DB::table('users')->where('email', 'sara.faculty@alignup.local')->value('id');
        $facultyOmar = DB::table('users')->where('email', 'omar.faculty@alignup.local')->value('id');

        $advisingId = DB::table('services')->where('name', 'Academic Advising')->value('id');
        $tutoringId = DB::table('services')->where('name', 'Tutoring')->value('id');

        // Dr. Sara - Advising: Mon & Wed 10:00–13:00
        DB::table('availability_rules')->insert([
            ['faculty_id' => $facultySara, 'service_id' => $advisingId, 'weekday' => 1, 'start_time' => '10:00:00', 'end_time' => '13:00:00', 'created_at' => $now, 'updated_at' => $now],
            ['faculty_id' => $facultySara, 'service_id' => $advisingId, 'weekday' => 3, 'start_time' => '10:00:00', 'end_time' => '13:00:00', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Mr. Omar - Tutoring: Tue & Thu 14:00–17:00
        DB::table('availability_rules')->insert([
            ['faculty_id' => $facultyOmar, 'service_id' => $tutoringId, 'weekday' => 2, 'start_time' => '14:00:00', 'end_time' => '17:00:00', 'created_at' => $now, 'updated_at' => $now],
            ['faculty_id' => $facultyOmar, 'service_id' => $tutoringId, 'weekday' => 4, 'start_time' => '14:00:00', 'end_time' => '17:00:00', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
