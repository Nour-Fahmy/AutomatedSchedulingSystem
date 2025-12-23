<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\GoogleCalendarService;
class AppointmentService
{
    /**
     * Get all appointments for the logged-in student
     */
    public function getStudentAppointments(int $studentId)
    {
        return Appointment::with(['faculty', 'service'])
            ->where('student_id', $studentId)
            ->orderBy('start_at', 'asc')
            ->get();
    }

    /**
     * Get available services for booking
     */
    public function getAvailableServices()
    {
        return Service::where('is_active', true)
            ->whereIn('name', ['Academic Advising', 'Career Counseling', 'Tutoring'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all faculty members
     */
    public function getFacultyMembers()
    {
        return User::where('type', 'faculty')
            ->orderBy('name')
            ->get();
    }

    /**
     * Check if there's a time conflict for a faculty member
     */
    public function hasConflict(int $facultyId, string $startAt, string $endAt): bool
    {
        return Appointment::where('faculty_id', $facultyId)
            ->where('status', 'confirmed')
            ->where(function ($q) use ($startAt, $endAt) {
                $q->whereBetween('start_at', [$startAt, $endAt])
                  ->orWhereBetween('end_at', [$startAt, $endAt])
                  ->orWhere(function ($q2) use ($startAt, $endAt) {
                      $q2->where('start_at', '<=', $startAt)
                         ->where('end_at', '>=', $endAt);
                  });
            })
            ->exists();
    }

    /**
     * Create a new appointment
     */
    public function createAppointment(array $data): Appointment
    {
        // Build start and end DateTime
        $startAt = $data['date'] . ' ' . $data['start_time'] . ':00';
        $endAt = date('Y-m-d H:i:s', strtotime($startAt . ' +' . $data['duration'] . ' minutes'));

        // Check for conflicts
        if ($this->hasConflict($data['faculty_id'], $startAt, $endAt)) {
            throw new \Exception('This faculty already has an appointment in that time range. Please choose another time.');
        }

        $appointment = Appointment::create([
            'student_id' => $data['student_id'],
            'faculty_id' => $data['faculty_id'],
            'service_id' => $data['service_id'],
            'start_at' => $startAt,
            'end_at' => $endAt,
            'status' => 'confirmed',
            'scheduled_by' => 'student',
            'reason' => $data['reason'] ?? null,
        ]);

        // Create Google Calendar events with notifications
        $googleCalendarService = new GoogleCalendarService();
        $googleEventId = $googleCalendarService->createEvent($appointment);
        
        // Save the Google event ID if created
        if ($googleEventId) {
            $appointment->update(['google_event_id' => $googleEventId]);
        }

        return $appointment;
    }

    /**
     * Cancel an appointment
     */
    public function cancelAppointment(Appointment $appointment, int $userId): void
    {
        $user = User::find($userId);
        
        // Verify ownership - either student or faculty can cancel
        if ($appointment->student_id !== $userId && $appointment->faculty_id !== $userId) {
            throw new \Exception('You do not have permission to cancel this appointment.');
        }

        // Check if appointment is in the past
        if ($appointment->start_at <= now()) {
            throw new \Exception('You can only cancel upcoming appointments.');
        }

        // Delete Google Calendar event if exists
        if ($appointment->google_event_id) {
            $googleCalendarService = new GoogleCalendarService();
            $appointment->load(['student', 'faculty']);
            
            // Delete from the calendar where it was created (student's calendar if they have token, otherwise faculty's)
            $owner = $appointment->student->google_token 
                ? $appointment->student 
                : ($appointment->faculty->google_token ? $appointment->faculty : null);
            
            if ($owner) {
                $googleCalendarService->deleteEvent($owner, $appointment->google_event_id);
            }
        }

        $appointment->update([
            'status' => 'canceled',
        ]);
    }

    /**
     * Get appointment data for create form
     */
    public function getCreateFormData(): array
    {
        return [
            'services' => $this->getAvailableServices(),
        ];
    }
}


