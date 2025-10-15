<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ---------------------------------------------
    // 🔗 Relationships
    // ---------------------------------------------

    /**
     * Appointments where this user is the student.
     */
    public function studentAppointments()
    {
        return $this->hasMany(Appointment::class, 'student_id');
    }

    /**
     * Appointments where this user is the faculty member.
     */
    public function facultyAppointments()
    {
        return $this->hasMany(Appointment::class, 'faculty_id');
    }

    /**
     * Availability rules (only relevant if the user is faculty).
     */
    public function availabilityRules()
    {
        return $this->hasMany(AvailabilityRule::class, 'faculty_id');
    }

    /**
     * Forum threads created by this user.
     */
    public function forumThreads()
    {
        return $this->hasMany(ForumThread::class, 'created_by');
    }

    /**
     * Forum posts made by this user.
     */
    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class, 'posted_by');
    }

    // ---------------------------------------------
    // 🧠 Helper Methods (optional)
    // ---------------------------------------------

    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    public function isFaculty(): bool
    {
        return $this->type === 'faculty';
    }

    public function isStudent(): bool
    {
        return $this->type === 'student';
    }
}
