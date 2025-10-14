<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('faculty_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();

            $table->dateTime('start_at');
            $table->dateTime('end_at');

            $table->enum('status', ['confirmed', 'canceled', 'completed'])->default('confirmed');
            $table->enum('scheduled_by', ['system', 'student', 'admin'])->default('system');
            $table->text('reason')->nullable();

            $table->timestamps();

            // Critical indexes for performance
            $table->index(['faculty_id', 'start_at', 'end_at']); // conflict checks
            $table->index(['student_id', 'start_at']);           // “my appointments” pages
            $table->index(['service_id', 'start_at']);           // optional, but handy
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
