@extends('layouts.layout')

@section('title', 'Settings - AlignUp')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Application Settings</h1>
            <p class="text-gray-600 mb-6 text-sm">
                These settings control how appointments and reminders behave in AlignUp.
            </p>

            @if (session('status'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-800 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Timezone -->
                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">
                        Timezone
                    </label>
                    <input
                        type="text"
                        id="timezone"
                        name="timezone"
                        value="{{ old('timezone', $settings['timezone'] ?? 'Africa/Cairo') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        Example: Africa/Cairo, Europe/London, America/New_York
                    </p>
                </div>

                <!-- Reschedule Cutoff -->
                <div>
                    <label for="reschedule_cutoff_minutes" class="block text-sm font-medium text-gray-700 mb-1">
                        Reschedule Cutoff (minutes)
                    </label>
                    <input
                        type="number"
                        id="reschedule_cutoff_minutes"
                        name="reschedule_cutoff_minutes"
                        min="0"
                        value="{{ old('reschedule_cutoff_minutes', $settings['reschedule_cutoff_minutes'] ?? '120') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        How many minutes before an appointment the user is allowed to reschedule.
                    </p>
                </div>

                <!-- Cancel Cutoff -->
                <div>
                    <label for="cancel_cutoff_minutes" class="block text-sm font-medium text-gray-700 mb-1">
                        Cancel Cutoff (minutes)
                    </label>
                    <input
                        type="number"
                        id="cancel_cutoff_minutes"
                        name="cancel_cutoff_minutes"
                        min="0"
                        value="{{ old('cancel_cutoff_minutes', $settings['cancel_cutoff_minutes'] ?? '120') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        How many minutes before an appointment the user is allowed to cancel.
                    </p>
                </div>

                <!-- Default Slot Length -->
                <div>
                    <label for="slot_length_default_minutes" class="block text-sm font-medium text-gray-700 mb-1">
                        Default Slot Length (minutes)
                    </label>
                    <input
                        type="number"
                        id="slot_length_default_minutes"
                        name="slot_length_default_minutes"
                        min="5"
                        value="{{ old('slot_length_default_minutes', $settings['slot_length_default_minutes'] ?? '30') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        Default duration of appointment slots.
                    </p>
                </div>

                <!-- Reminder Lead Times -->
                <div>
                    <label for="reminder_lead_times" class="block text-sm font-medium text-gray-700 mb-1">
                        Reminder Lead Times
                    </label>
                    <input
                        type="text"
                        id="reminder_lead_times"
                        name="reminder_lead_times"
                        value="{{ old('reminder_lead_times', $reminderLeadTimes) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        Comma-separated values, e.g. <span class="font-mono">24h, 2h</span>
                    </p>
                </div>

                <div class="pt-4 flex justify-end">
                    <button
                        type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 font-medium"
                    >
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection




