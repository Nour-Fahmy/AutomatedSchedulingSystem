<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Services\AppointmentService;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Show the logged-in student's appointments (acts as "View Schedule").
     */
    public function index()
    {
        $user = Auth::user();
        $appointments = $this->appointmentService->getStudentAppointments($user->id);

        return view('appointments.index', [
            'appointments' => $appointments,
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
     * Store a newly booked appointment.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'faculty_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:240',
            'reason' => 'nullable|string|max:1000',
        ]);

        try {
            $data['student_id'] = $user->id;
            $this->appointmentService->createAppointment($data);

            return redirect()->route('appointments.index')
                ->with('status', 'Appointment booked successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => $e->getMessage()]);
        }
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
