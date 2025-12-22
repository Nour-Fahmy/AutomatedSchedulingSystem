<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $rows = [
            ['key' => 'reschedule_cutoff_minutes', 'value' => '120'],
            ['key' => 'cancel_cutoff_minutes',     'value' => '120'],
            ['key' => 'reminder_lead_times',       'value' => '["24h","2h"]'],
            ['key' => 'search_horizon_days',       'value' => '14'],
            ['key' => 'slot_length_default_minutes','value' => '30'],
            ['key' => 'forum_enabled',             'value' => 'true'],
            ['key' => 'ics_enabled',               'value' => 'true'],
            ['key' => 'timezone',                  'value' => 'Africa/Cairo'],
            ['key' => 'mail_from_address',         'value' => 'no-reply@alignup.local'],
            ['key' => 'no_show_grace_minutes',        'value' => '15'],
            ['key' => 'appointment_duration_minutes','value' => '30'],

        ];

        foreach ($rows as $row) {
            DB::table('settings')->updateOrInsert(
                ['key' => $row['key']],
                ['value' => $row['value'], 'updated_at' => $now, 'created_at' => $now]
            );
        }
    }
}
