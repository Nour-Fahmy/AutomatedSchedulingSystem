<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('availability_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();

            // 0 = Sunday ... 6 = Saturday (keep consistent in your UI)
            $table->tinyInteger('weekday'); 
            $table->time('start_time');
            $table->time('end_time');

            $table->timestamps();

            // Help find rules quickly and avoid duplicates per day
            $table->index(['faculty_id', 'service_id', 'weekday']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availability_rules');
    }
};
