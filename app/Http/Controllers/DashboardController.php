<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\Request;

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
}
