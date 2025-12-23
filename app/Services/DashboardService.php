<?php

namespace App\Services;

use App\Models\User;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Appointment;
use App\Models\AvailabilityRule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get dashboard data for admin user
     */
    public function getAdminDashboardData(string $range): array
    {
        $from = $this->getDateFromRange($range);

        $usersCount = $this->userService->getUserCounts();

        $appointmentsQuery = Appointment::query();
        if ($from) {
            $appointmentsQuery->where('created_at', '>=', $from);
        }

        $appointmentsCount = [
            'confirmed' => (clone $appointmentsQuery)->where('status', 'confirmed')->count(),
            'completed' => (clone $appointmentsQuery)->where('status', 'completed')->count(),
            'canceled'  => (clone $appointmentsQuery)->where('status', 'canceled')->count(),
            'no_show'   => (clone $appointmentsQuery)->where('status', 'no-show')->count(),
            'total'     => (clone $appointmentsQuery)->count(),
        ];

        // Resource utilization by service
        $utilByService = (clone $appointmentsQuery)
            ->select('service_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('service_id')
            ->pluck('cnt', 'service_id');

        // Slot utilization
        $slotUtilization = $this->calculateSlotUtilization($from);

        // Users list for admin management
        $users = $this->userService->getAllUsers();

        // Settings list
        $settings = Setting::orderBy('key')->get();

        // Services list
        $services = Service::orderBy('name')->get();

        return [
            'usersCount' => $usersCount,
            'appointmentsCount' => $appointmentsCount,
            'utilByService' => $utilByService,
            'slotUtilization' => $slotUtilization,
            'users' => $users,
            'settings' => $settings,
            'services' => $services,
        ];
    }

    /**
     * Get dashboard data for faculty user
     */
    public function getFacultyDashboardData(User $user, string $range): array
    {
        $from = $this->getDateFromRange($range);

        $myAppts = Appointment::where('faculty_id', $user->id);
        if ($from) {
            $myAppts->where('created_at', '>=', $from);
        }

        $appointmentsCount = [
            'confirmed' => (clone $myAppts)->where('status', 'confirmed')->count(),
            'completed' => (clone $myAppts)->where('status', 'completed')->count(),
            'canceled'  => (clone $myAppts)->where('status', 'canceled')->count(),
            'no_show'   => (clone $myAppts)->where('status', 'no-show')->count(),
            'total'     => (clone $myAppts)->count(),
        ];

        $upcoming = Appointment::with(['student', 'service'])
            ->where('faculty_id', $user->id)
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->limit(10)
            ->get();

        $rules = AvailabilityRule::with('service')
            ->where('faculty_id', $user->id)
            ->orderBy('weekday')
            ->orderBy('start_time')
            ->get();

        $services = Service::where('is_active', true)
            ->whereIn('name', ['Academic Advising', 'Career Counseling', 'Tutoring'])
            ->orderBy('name')
            ->get();

        return [
            'appointmentsCount' => $appointmentsCount,
            'upcoming' => $upcoming,
            'rules' => $rules,
            'services' => $services,
        ];
    }

    /**
     * Get dashboard data for student user
     */
    public function getStudentDashboardData(User $user, string $range): array
    {
        $from = $this->getDateFromRange($range);

        $myAppts = Appointment::where('student_id', $user->id);
        if ($from) {
            $myAppts->where('created_at', '>=', $from);
        }

        $appointmentsCount = [
            'confirmed' => (clone $myAppts)->where('status', 'confirmed')->count(),
            'completed' => (clone $myAppts)->where('status', 'completed')->count(),
            'canceled'  => (clone $myAppts)->where('status', 'canceled')->count(),
            'no_show'   => (clone $myAppts)->where('status', 'no-show')->count(),
            'total'     => (clone $myAppts)->count(),
        ];

        $upcoming = Appointment::with(['faculty', 'service'])
            ->where('student_id', $user->id)
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->limit(10)
            ->get();

        $services = Service::orderBy('name')->get();

        return [
            'appointmentsCount' => $appointmentsCount,
            'upcoming' => $upcoming,
            'services' => $services,
        ];
    }

    /**
     * Update a setting (admin only)
     */
    public function updateSetting(array $data): void
    {
        // If ID is provided, update existing setting
        if (!empty($data['id'])) {
            $setting = Setting::findOrFail($data['id']);
            $setting->update([
                'key' => $data['key'],
                'value' => $data['value'] ?? '',
            ]);
            return;
        }

        // Otherwise, create or update by key
        Setting::updateOrCreate(
            ['key' => $data['key']],
            ['value' => $data['value'] ?? '']
        );
    }

    /**
     * Delete a setting (admin only)
     */
    public function deleteSetting(int $id): void
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();
    }

    /**
     * Toggle service active status (admin only)
     */
    public function toggleService(int $serviceId, bool $isActive): void
    {
        Service::where('id', $serviceId)
            ->update(['is_active' => $isActive]);
    }

    /**
     * Calculate slot utilization based on availability rules and appointments
     */
    private function calculateSlotUtilization(?Carbon $from): string
    {
        $durationSetting = Setting::where('key', 'appointment_duration_minutes')->value('value');
        $durationMinutes = is_numeric($durationSetting) ? (int)$durationSetting : null;

        if (!$durationMinutes || $durationMinutes <= 0) {
            return 'NA';
        }

        [$totalSlots, $bookedSlots] = $this->computeSlotUtilization($from, $durationMinutes);
        
        return $totalSlots > 0 ? round(($bookedSlots / $totalSlots) * 100, 1) . '%' : 'NA';
    }

    /**
     * Compute slot utilization (total slots vs booked slots)
     */
    private function computeSlotUtilization(?Carbon $from, int $durationMinutes): array
    {
        $rules = AvailabilityRule::all();

        $startDate = $from ? $from->copy()->startOfDay() : Carbon::now()->subDays(365)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $totalSlots = 0;

        // Generate slots per day in range
        for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
            $weekday = (int)$d->dayOfWeek; // 0=Sun..6=Sat
            foreach ($rules as $r) {
                if ((int)$r->weekday !== $weekday) {
                    continue;
                }

                $start = Carbon::parse($d->toDateString() . ' ' . $r->start_time);
                $end = Carbon::parse($d->toDateString() . ' ' . $r->end_time);

                // Number of full slots
                while ($start->copy()->addMinutes($durationMinutes)->lte($end)) {
                    $totalSlots++;
                    $start->addMinutes($durationMinutes);
                }
            }
        }

        $bookedSlotsQuery = Appointment::whereIn('status', ['confirmed', 'completed', 'no-show']);
        if ($from) {
            $bookedSlotsQuery->where('created_at', '>=', $from);
        }
        $bookedSlots = $bookedSlotsQuery->count();

        return [$totalSlots, $bookedSlots];
    }

    /**
     * Get date from range parameter
     */
    private function getDateFromRange(string $range): ?Carbon
    {
        if ($range === 'all') {
            return null;
        }

        return Carbon::now()->subDays((int)$range);
    }
}

