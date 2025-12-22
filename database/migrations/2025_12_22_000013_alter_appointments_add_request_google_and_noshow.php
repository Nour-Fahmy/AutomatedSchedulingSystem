<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('request_id')
                ->nullable()
                ->after('id')
                ->constrained('appointment_requests')
                ->nullOnDelete();

            $table->string('google_event_id')->nullable()->after('reason');
            $table->dateTime('reminders_sent_at')->nullable()->after('google_event_id');
        });

        // MySQL: extend enum values (your current enum is confirmed/canceled/completed)
        DB::statement(
            "ALTER TABLE appointments 
             MODIFY status ENUM('confirmed','canceled','completed','no-show') 
             NOT NULL DEFAULT 'confirmed'"
        );
    }

    public function down(): void
    {
        // revert enum
        DB::statement(
            "ALTER TABLE appointments 
             MODIFY status ENUM('confirmed','canceled','completed') 
             NOT NULL DEFAULT 'confirmed'"
        );

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('request_id');
            $table->dropColumn(['google_event_id', 'reminders_sent_at']);
        });
    }
};
