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

            $table->date('preferred_date');

            $table->enum('time_bracket', ['morning', 'afternoon', 'evening', 'any'])
                ->default('any');

            $table->enum('status', ['pending', 'matched', 'expired'])
                ->default('pending');

            $table->timestamps();

            $table->index(['student_id', 'preferred_date']);
            $table->index(['service_id', 'preferred_date']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_requests');
    }
};
