<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SettingsService;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Show settings page (global application settings).
     */
    public function index()
    {
        $data = $this->settingsService->getSettingsForView();
        
        return view('settings', $data);
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'timezone' => 'required|string',
            'reschedule_cutoff_minutes' => 'required|integer|min:0',
            'cancel_cutoff_minutes' => 'required|integer|min:0',
            'slot_length_default_minutes' => 'required|integer|min:5',
            'reminder_lead_times' => 'required|string',
        ]);

        // Update settings using the service
        $this->settingsService->updateSetting('timezone', $data['timezone']);
        $this->settingsService->updateSetting('reschedule_cutoff_minutes', (string) $data['reschedule_cutoff_minutes']);
        $this->settingsService->updateSetting('cancel_cutoff_minutes', (string) $data['cancel_cutoff_minutes']);
        $this->settingsService->updateSetting('slot_length_default_minutes', (string) $data['slot_length_default_minutes']);
        $this->settingsService->saveReminderLeadTimes($data['reminder_lead_times']);

        return back()->with('status', 'Settings have been updated successfully.');
    }
}
