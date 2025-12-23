<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\AppointmentRequest;
use App\Services\AppointmentService;
use App\Services\AppointmentMatchingService;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Show the logged-in user's appointments (acts as "View Schedule").
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get appointments based on user type
        if ($user->isStudent()) {
            $appointments = $this->appointmentService->getStudentAppointments($user->id);
        } elseif ($user->isFaculty()) {
            $appointments = Appointment::with(['student', 'service'])
                ->where('faculty_id', $user->id)
                ->orderBy('start_at', 'asc')
                ->get();
        } else {
            $appointments = collect();
        }

        return view('appointments.index', [
            'appointments' => $appointments,
            'user' => $user,
        ]);
    }

    /**
     * Show the booking form for a new appointment.
     */
    public function create()
    {
        $data = $this->appointmentService->getCreateFormData();

        return view('appointments.create', $data);
    }

    /**
     * Store a student availability request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'available_days' => 'required|array|min:1',
            'available_days.*' => 'integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Check if student already has a pending request for this service
        $existingRequest = AppointmentRequest::where('student_id', $user->id)
            ->where('service_id', $validated['service_id'])
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()
                ->withInput()
                ->withErrors(['service_id' => 'You already have a pending request for this service. Please wait for it to be matched.']);
        }

        $appointmentRequest = AppointmentRequest::create([
            'student_id' => $user->id,
            'service_id' => $validated['service_id'],
            'available_days' => $validated['available_days'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'pending',
        ]);

        // Trigger matching process
        $matchingService = app(AppointmentMatchingService::class);
        $matchedAppointment = $matchingService->tryMatchRequest($appointmentRequest);

        if ($matchedAppointment) {
            return redirect()->route('appointments.index')
                ->with('status', 'Your availability has been submitted and a matching appointment has been scheduled!');
        }

        return redirect()->route('appointments.index')
            ->with('status', 'Your availability has been submitted. The system will automatically find a matching slot with a faculty member when one becomes available.');
    }

    /**
     * Cancel an appointment (student).
     */
    public function cancel(Appointment $appointment)
    {
        $user = Auth::user();

        try {
            $this->appointmentService->cancelAppointment($appointment, $user->id);

            return back()->with('status', 'Appointment canceled successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['appointment' => $e->getMessage()]);
        }
    }
}
