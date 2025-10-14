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

        DB::table('services')->insert([
            ['name' => 'Academic Advising',  'description' => 'Program & course planning',        'is_active' => true,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tutoring',           'description' => 'One-on-one tutoring sessions',     'is_active' => true,  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Career Counseling',  'description' => 'CV & interview guidance',          'is_active' => false, 'created_at' => $now, 'updated_at' => $now], // inactive to demo
        ]);
    }
}
