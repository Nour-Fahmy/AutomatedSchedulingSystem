<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            // Available days: JSON array of weekday numbers (0=Sunday, 1=Monday, ..., 6=Saturday)
            $table->json('available_days');

            // Time range when student is available
            $table->time('start_time');
            $table->time('end_time');

            $table->enum('status', ['pending', 'matched', 'expired'])
                ->default('pending');

            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['service_id', 'status']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_requests');
    }
};
