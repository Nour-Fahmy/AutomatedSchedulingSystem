<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AvailabilityRule;
use App\Models\Appointment;
use App\Services\DashboardService;
use App\Services\AppointmentMatchingService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/auth/login');
        }

        // Range filter: 7 / 30 / all
        $range = $request->query('range', '7');
        $range = in_array($range, ['7', '30', 'all'], true) ? $range : '7';

        // Get dashboard data based on user role
        if ($user->isAdmin()) {
            $data = $this->dashboardService->getAdminDashboardData($range);
            return view('dashboard', array_merge([
                'user' => $user,
                'range' => $range,
            ], $data));
        }

        if ($user->isFaculty()) {
            $data = $this->dashboardService->getFacultyDashboardData($user, $range);
            return view('dashboard', array_merge([
                'user' => $user,
                'range' => $range,
            ], $data));
        }

        // Student
        $data = $this->dashboardService->getStudentDashboardData($user, $range);
        return view('dashboard', array_merge([
            'user' => $user,
            'range' => $range,
        ], $data));
    }

    // Admin actions (keep flow: admin adjusts settings + toggles services)
    public function updateSetting(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) abort(403);

        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'nullable|string|max:5000',
            'id' => 'nullable|integer|exists:settings,id',
        ]);

        $this->dashboardService->updateSetting($validated);

        $message = !empty($validated['id']) ? 'Setting updated.' : 'Setting saved.';
        return back()->with('success', $message);
    }

    public function deleteSetting($id)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) abort(403);

        $this->dashboardService->deleteSetting($id);

        return back()->with('success', 'Setting deleted.');
    }

    public function toggleService(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) abort(403);

        $validated = $request->validate([
            'service_id' => 'required|integer|exists:services,id',
            'is_active' => 'required|boolean',
        ]);

        $this->dashboardService->toggleService($validated['service_id'], $validated['is_active']);

        return back()->with('success', 'Service updated.');
    }

    /**
     * Store faculty office hours
     */
    public function storeFacultyOfficeHours(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isFaculty()) {
            abort(403);
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'weekday' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Check if this rule already exists
        $existing = AvailabilityRule::where('faculty_id', $user->id)
            ->where('service_id', $validated['service_id'])
            ->where('weekday', $validated['weekday'])
            ->where('start_time', $validated['start_time'] . ':00')
            ->where('end_time', $validated['end_time'] . ':00')
            ->first();

        if ($existing) {
            return back()->with('error', 'This office hour slot already exists.');
        }

        AvailabilityRule::create([
            'faculty_id' => $user->id,
            'service_id' => $validated['service_id'],
            'weekday' => $validated['weekday'],
            'start_time' => $validated['start_time'] . ':00',
            'end_time' => $validated['end_time'] . ':00',
        ]);

        // Trigger matching process for pending requests
        $matchingService = app(AppointmentMatchingService::class);
        $matchingService->tryMatchPendingRequests($validated['service_id']);

        return back()->with('success', 'Office hours added successfully. The system will check for matching student requests.');
    }

    /**
     * Delete faculty office hours
     */
    public function deleteFacultyOfficeHours(AvailabilityRule $availabilityRule)
    {
        $user = auth()->user();
        if ($availabilityRule->faculty_id !== $user->id) {
            abort(403);
        }

        // Check if there are any upcoming appointments scheduled during this office hours slot
        $upcomingAppointments = Appointment::where('faculty_id', $user->id)
            ->where('service_id', $availabilityRule->service_id)
            ->where('status', 'confirmed')
            ->where('start_at', '>', now())
            ->get();

        // Filter appointments that match this availability rule (same weekday and time range)
        $matchingAppointments = $upcomingAppointments->filter(function ($appointment) use ($availabilityRule) {
            $appointmentDate = Carbon::parse($appointment->start_at);
            $appointmentWeekday = $appointmentDate->dayOfWeek; // 0=Sunday, 6=Saturday
            $appointmentTime = $appointmentDate->format('H:i:s');
            
            // Check if weekday matches
            if ($appointmentWeekday != $availabilityRule->weekday) {
                return false;
            }
            
            // Check if appointment time falls within the availability rule's time range
            $ruleStart = $this->normalizeTime($availabilityRule->start_time);
            $ruleEnd = $this->normalizeTime($availabilityRule->end_time);
            
            return $appointmentTime >= $ruleStart && $appointmentTime < $ruleEnd;
        });

        if ($matchingAppointments->count() > 0) {
            $appointmentCount = $matchingAppointments->count();
            return back()->with('error', "Cannot delete this office hour slot. You have {$appointmentCount} upcoming appointment(s) scheduled during this time. Please cancel the appointment(s) first.");
        }

        $availabilityRule->delete();

        return back()->with('success', 'Office hour slot deleted successfully.');
    }

    /**
     * Normalize time to H:i:s format for comparison
     */
    private function normalizeTime($time): string
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
