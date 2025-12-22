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

        // Fetch IDs safely (no array-shaped where)
        $facultyId = DB::table('users')
            ->where('email', 'faculty@alignup.local')
            ->value('id');

        $tutoringId = DB::table('services')
            ->where('name', 'Tutoring')
            ->value('id');

        $advisingId = DB::table('services')
            ->where('name', 'Academic Advising')
            ->value('id');

        $careerId = DB::table('services')
            ->where('name', 'Career Counseling')
            ->value('id');

        // If prerequisites are missing, stop (prevents FK issues)
        if (!$facultyId || !$tutoringId || !$advisingId || !$careerId) {
            return;
        }

        /**
         * Weekday mapping (LOCKED):
         * 1=Monday, 2=Tuesday, 3=Wednesday, 4=Thursday, 5=Friday, 6=Saturday, 7=Sunday
         */
        $rules = [
            // Tutoring (Mon)
            [
                'faculty_id'  => $facultyId,
                'service_id'  => $tutoringId,
                'weekday'     => 1,
                'start_time'  => '09:00:00',
                'end_time'    => '12:00:00',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Tutoring (Wed)
            [
                'faculty_id'  => $facultyId,
                'service_id'  => $tutoringId,
                'weekday'     => 3,
                'start_time'  => '13:00:00',
                'end_time'    => '16:00:00',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Academic Advising (Tue)
            [
                'faculty_id'  => $facultyId,
                'service_id'  => $advisingId,
                'weekday'     => 2,
                'start_time'  => '10:00:00',
                'end_time'    => '13:00:00',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Career Counseling (Thu)
            [
                'faculty_id'  => $facultyId,
                'service_id'  => $careerId,
                'weekday'     => 4,
                'start_time'  => '11:00:00',
                'end_time'    => '14:00:00',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        /**
         * Idempotent upsert:
         * Same rule won't be duplicated on repeated db:seed runs.
         * Unique identity = faculty + service + weekday + start + end
         */
        DB::table('availability_rules')->upsert(
            $rules,
            ['faculty_id', 'service_id', 'weekday', 'start_time', 'end_time'],
            ['updated_at']
        );
    }
}
