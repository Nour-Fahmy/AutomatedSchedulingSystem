<?php

namespace App\Services;

use App\Models\AppointmentRequest;
use App\Models\AvailabilityRule;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

class AppointmentMatchingService
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Try to match a specific appointment request with available faculty.
     */
    public function tryMatchRequest(AppointmentRequest $request): ?Appointment
    {
        // Get all faculty members who have office hours for this service
        $facultyMembers = User::where('type', 'faculty')
            ->whereHas('availabilityRules', function ($query) use ($request) {
                $query->where('service_id', $request->service_id);
            })
            ->get();

        foreach ($facultyMembers as $faculty) {
            $appointment = $this->findMatchingSlot($request, $faculty);
            if ($appointment) {
                return $appointment;
            }
        }

        return null;
    }

    /**
     * Try to match pending requests for a specific service.
     */
    public function tryMatchPendingRequests(int $serviceId): void
    {
        $pendingRequests = AppointmentRequest::where('service_id', $serviceId)
            ->where('status', 'pending')
            ->get();

        foreach ($pendingRequests as $request) {
            $this->tryMatchRequest($request);
        }
    }

    /**
     * Find a matching slot between student request and faculty availability.
     */
    protected function findMatchingSlot(AppointmentRequest $request, User $faculty): ?Appointment
    {
        $facultyRules = AvailabilityRule::where('faculty_id', $faculty->id)
            ->where('service_id', $request->service_id)
            ->get();

        // Get the next 4 weeks to search for available slots
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->addWeeks(4)->endOfDay();

        foreach ($facultyRules as $rule) {
            // Check if this rule's weekday matches any of the student's available days
            if (!in_array($rule->weekday, $request->available_days)) {
                continue;
            }

            // Find overlapping time range - normalize time formats
            $requestStart = $this->normalizeTime($request->start_time);
            $requestEnd = $this->normalizeTime($request->end_time);
            $ruleStart = $this->normalizeTime($rule->start_time);
            $ruleEnd = $this->normalizeTime($rule->end_time);
            
            $overlapStart = $this->getLaterTime($requestStart, $ruleStart);
            $overlapEnd = $this->getEarlierTime($requestEnd, $ruleEnd);

            if ($overlapStart >= $overlapEnd) {
                continue; // No overlap
            }

            // Calculate appointment duration (default 30 minutes, or use available time if less)
            $duration = min(30, $this->timeDifferenceInMinutes($overlapStart, $overlapEnd));

            // Find the next occurrence of this weekday
            $currentDate = $startDate->copy();
            $checkedWeeks = 0;
            $maxWeeks = 4;
            
            while ($checkedWeeks < $maxWeeks) {
                // Find next occurrence of the weekday
                $daysUntilWeekday = ($rule->weekday - $currentDate->dayOfWeek + 7) % 7;
                if ($daysUntilWeekday === 0 && $currentDate->dayOfWeek !== $rule->weekday) {
                    $daysUntilWeekday = 7;
                }

                $appointmentDate = $currentDate->copy()->addDays($daysUntilWeekday);
                
                if ($appointmentDate > $endDate) {
                    break;
                }

                // Build full datetime
                $startDateTime = Carbon::parse($appointmentDate->format('Y-m-d') . ' ' . $overlapStart);
                $endDateTime = $startDateTime->copy()->addMinutes($duration);

                // Check if this slot is available (no conflicts)
                if ($startDateTime > now() && !$this->appointmentService->hasConflict($faculty->id, $startDateTime->format('Y-m-d H:i:s'), $endDateTime->format('Y-m-d H:i:s'))) {
                    // Create the appointment
                    $appointment = Appointment::create([
                        'student_id' => $request->student_id,
                        'faculty_id' => $faculty->id,
                        'service_id' => $request->service_id,
                        'request_id' => $request->id,
                        'start_at' => $startDateTime,
                        'end_at' => $endDateTime,
                        'status' => 'confirmed',
                        'scheduled_by' => 'system',
                    ]);

                    // Mark request as matched
                    $request->update(['status' => 'matched']);

                    return $appointment;
                }

                // Move to next week
                $currentDate = $appointmentDate->copy()->addDays(1);
                $checkedWeeks++;
            }
        }

        return null;
    }

    protected function getLaterTime(string $time1, string $time2): string
    {
        return strtotime($time1) > strtotime($time2) ? $time1 : $time2;
    }

    protected function getEarlierTime(string $time1, string $time2): string
    {
        return strtotime($time1) < strtotime($time2) ? $time1 : $time2;
    }

    protected function timeDifferenceInMinutes(string $start, string $end): int
    {
        $startMinutes = (int)date('H', strtotime($start)) * 60 + (int)date('i', strtotime($start));
        $endMinutes = (int)date('H', strtotime($end)) * 60 + (int)date('i', strtotime($end));
        return $endMinutes - $startMinutes;
    }

    /**
     * Normalize time to H:i:s format for comparison
     */
    protected function normalizeTime($time): string
    {
        if (is_string($time)) {
            // If it's already in H:i:s format, return as is
            if (strlen($time) === 8) {
                return $time;
            }
            // If it's in H:i format, add :00
            if (strlen($time) === 5) {
                return $time . ':00';
            }
            return $time;
        }
        // If it's a Carbon/DateTime object
        return $time->format('H:i:s');
    }
}

