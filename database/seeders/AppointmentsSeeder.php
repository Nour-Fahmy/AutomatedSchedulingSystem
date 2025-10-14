<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $studentNour = DB::table('users')->where('email', 'nour.student@alignup.local')->value('id');
        $studentMina = DB::table('users')->where('email', 'mina.student@alignup.local')->value('id');
        $studentRana = DB::table('users')->where('email', 'rana.student@alignup.local')->value('id');

        $facultySara = DB::table('users')->where('email', 'sara.faculty@alignup.local')->value('id');
        $facultyOmar = DB::table('users')->where('email', 'omar.faculty@alignup.local')->value('id');

        $advisingId = DB::table('services')->where('name', 'Academic Advising')->value('id');
        $tutoringId = DB::table('services')->where('name', 'Tutoring')->value('id');

        // Helper to next weekday (0=Sun..6=Sat)
        $nextWeekday = function (int $weekday) {
            $d = Carbon::today();
            while ((int)$d->dayOfWeek !== $weekday) {
                $d->addDay();
            }
            // if today is same weekday but time passed, still ok—we'll set explicit times below
            if ($d->lessThan(Carbon::today())) {
                $d->addWeek();
            }
            return $d;
        };

        // Sara (Advising) Monday 10:30–11:00
        $monday = $nextWeekday(1)->setTime(10, 30, 0);
        DB::table('appointments')->insert([
            'student_id'  => $studentNour,
            'faculty_id'  => $facultySara,
            'service_id'  => $advisingId,
            'start_at'    => $monday->toDateTimeString(),
            'end_at'      => $monday->copy()->addMinutes(30)->toDateTimeString(),
            'status'      => 'confirmed',
            'scheduled_by'=> 'admin',
            'reason'      => 'Initial advising',
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        // Sara (Advising) Wednesday 11:00–11:30
        $wednesday = $nextWeekday(3)->setTime(11, 0, 0);
        DB::table('appointments')->insert([
            'student_id'  => $studentMina,
            'faculty_id'  => $facultySara,
            'service_id'  => $advisingId,
            'start_at'    => $wednesday->toDateTimeString(),
            'end_at'      => $wednesday->copy()->addMinutes(30)->toDateTimeString(),
            'status'      => 'confirmed',
            'scheduled_by'=> 'admin',
            'reason'      => 'Course planning',
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        // Omar (Tutoring) Tuesday 14:00–14:30
        $tuesday = $nextWeekday(2)->setTime(14, 0, 0);
        DB::table('appointments')->insert([
            'student_id'  => $studentRana,
            'faculty_id'  => $facultyOmar,
            'service_id'  => $tutoringId,
            'start_at'    => $tuesday->toDateTimeString(),
            'end_at'      => $tuesday->copy()->addMinutes(30)->toDateTimeString(),
            'status'      => 'confirmed',
            'scheduled_by'=> 'admin',
            'reason'      => 'Math tutoring',
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);
    }
}
