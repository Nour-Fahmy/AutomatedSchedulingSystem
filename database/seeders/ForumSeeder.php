<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        // Seed forum only once (content, not configuration)
        if (DB::table('forum_threads')->exists()) {
            return;
        }

        $now = Carbon::now();

        // IDs must match UsersTableSeeder emails
        $adminId   = DB::table('users')->where('email', 'admin@alignup.local')->value('id');
        $facultyId = DB::table('users')->where('email', 'faculty@alignup.local')->value('id');
        $studentId = DB::table('users')->where('email', 'student@alignup.local')->value('id');

        // Services must match ServicesTableSeeder names
        $advisingId = DB::table('services')->where('name', 'Academic Advising')->value('id');
        $tutoringId = DB::table('services')->where('name', 'Tutoring')->value('id');

        // Guard: if prerequisites missing, stop cleanly
        if (!$adminId || !$facultyId || !$studentId || !$advisingId || !$tutoringId) {
            return;
        }

        // Thread 1 (student starts)
        $thread1Id = DB::table('forum_threads')->insertGetId([
            'title'      => 'How to prepare for advising session?',
            'service_id' => $advisingId,
            'created_by' => $studentId,
            'is_locked'  => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Posts for Thread 1 (NOTE: column name is body, not content)
        DB::table('forum_posts')->insert([
            [
                'thread_id'   => $thread1Id,
                'posted_by'   => $studentId,
                'body'        => 'What should I bring or prepare before meeting an advisor?',
                'is_answer'   => false,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'thread_id'   => $thread1Id,
                'posted_by'   => $facultyId,
                'body'        => 'Bring your transcript and a draft plan of courses. Write your questions in advance.',
                'is_answer'   => true,
                'created_at'  => $now->copy()->addMinutes(5),
                'updated_at'  => $now->copy()->addMinutes(5),
            ],
        ]);

        // Thread 2 (admin tips)
        $thread2Id = DB::table('forum_threads')->insertGetId([
            'title'      => 'Forum rules & scheduling tips',
            'service_id' => $tutoringId,
            'created_by' => $adminId,
            'is_locked'  => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('forum_posts')->insert([
            [
                'thread_id'   => $thread2Id,
                'posted_by'   => $adminId,
                'body'        => 'Be respectful. No personal data. Use clear titles. Cancel/reschedule early when possible.',
                'is_answer'   => false,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);
    }
}
