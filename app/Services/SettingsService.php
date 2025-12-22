<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    /**
     * Get all settings as key => value array
     */
    public function getAllSettings(): array
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    /**
     * Get a single setting value by key
     */
    public function getSetting(string $key, ?string $default = null): ?string
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Get formatted reminder lead times (comma-separated string)
     */
    public function getReminderLeadTimes(): string
    {
        $value = $this->getSetting('reminder_lead_times', '["24h","2h"]');
        $decoded = json_decode($value, true);
        
        if (is_array($decoded)) {
            return implode(', ', $decoded);
        }
        
        return '24h, 2h'; // Default fallback
    }

    /**
     * Update a single setting
     */
    public function updateSetting(string $key, string $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Update multiple settings at once
     */
    public function updateSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->updateSetting($key, (string) $value);
        }
    }

    /**
     * Parse and save reminder lead times from comma-separated string
     */
    public function saveReminderLeadTimes(string $leadTimesString): void
    {
        $leadTimes = array_values(
            array_filter(
                array_map('trim', explode(',', $leadTimesString))
            )
        );
        
        $this->updateSetting('reminder_lead_times', json_encode($leadTimes));
    }

    /**
     * Get settings data formatted for the settings view
     */
    public function getSettingsForView(): array
    {
        $settings = $this->getAllSettings();
        
        return [
            'settings' => $settings,
            'reminderLeadTimes' => $this->getReminderLeadTimes(),
        ];
    }
}


