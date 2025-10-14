<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $adminId   = DB::table('users')->where('email', 'admin@alignup.local')->value('id');
        $saraId    = DB::table('users')->where('email', 'sara.faculty@alignup.local')->value('id');
        $omarId    = DB::table('users')->where('email', 'omar.faculty@alignup.local')->value('id');
        $nourId    = DB::table('users')->where('email', 'nour.student@alignup.local')->value('id');
        $minaId    = DB::table('users')->where('email', 'mina.student@alignup.local')->value('id');

        $advisingId  = DB::table('services')->where('name', 'Academic Advising')->value('id');
        $tutoringId  = DB::table('services')->where('name', 'Tutoring')->value('id');

        // Thread 1 (Advising)
        $thread1Id = DB::table('forum_threads')->insertGetId([
            'title'       => 'How to prepare for advising session?',
            'service_id'  => $advisingId,
            'created_by'  => $nourId,
            'is_locked'   => false,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        DB::table('forum_posts')->insert([
            [
                'thread_id'  => $thread1Id,
                'posted_by'  => $nourId,
                'body'       => 'Any documents I should bring to my advising session?',
                'is_answer'  => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'thread_id'  => $thread1Id,
                'posted_by'  => $saraId,
                'body'       => 'Bring your transcript and a draft plan of courses for next semester.',
                'is_answer'  => true, // mark as accepted answer
                'created_at' => $now->copy()->addMinutes(5),
                'updated_at' => $now->copy()->addMinutes(5),
            ],
        ]);

        // Thread 2 (Tutoring)
        $thread2Id = DB::table('forum_threads')->insertGetId([
            'title'       => 'Tutoring availability for calculus this week',
            'service_id'  => $tutoringId,
            'created_by'  => $minaId,
            'is_locked'   => false,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        DB::table('forum_posts')->insert([
            [
                'thread_id'  => $thread2Id,
                'posted_by'  => $minaId,
                'body'       => 'Is there a slot on Tuesday afternoon?',
                'is_answer'  => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'thread_id'  => $thread2Id,
                'posted_by'  => $omarId,
                'body'       => 'Yes, I’m available from 14:00 to 17:00 on Tuesday.',
                'is_answer'  => true,
                'created_at' => $now->copy()->addMinutes(7),
                'updated_at' => $now->copy()->addMinutes(7),
            ],
            [
                'thread_id'  => $thread2Id,
                'posted_by'  => $adminId,
                'body'       => 'Reminder: cancellations must be ≥2 hours before start.',
                'is_answer'  => false,
                'created_at' => $now->copy()->addMinutes(12),
                'updated_at' => $now->copy()->addMinutes(12),
            ],
        ]);
    }
}
