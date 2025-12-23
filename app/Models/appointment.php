<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'faculty_id',
        'service_id',
        'request_id',
        'start_at',
        'end_at',
        'status',
        'scheduled_by',
        'reason',
    ];

    // ---------------------------------------------
    // 🔗 Relationships
    // ---------------------------------------------

    /**
     * Get the student who booked this appointment.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the faculty member assigned to this appointment.
     */
    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    /**
     * Get the service for this appointment.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the appointment request that led to this appointment (if any).
     */
    public function request()
    {
        return $this->belongsTo(AppointmentRequest::class, 'request_id');
    }

    // ---------------------------------------------
    // 🧠 Helper Methods (optional)
    // ---------------------------------------------

    /**
     * Check if the appointment is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->start_at > now();
    }

    /**
     * Check if the appointment is currently active.
     */
    public function isOngoing(): bool
    {
        return $this->start_at <= now() && $this->end_at >= now();
    }

    /**
     * Check if the appointment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
