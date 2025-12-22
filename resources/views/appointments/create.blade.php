@extends('layouts.layout')

@section('title', 'Book Appointment - AlignUp')

@section('content')
<div class="bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Book a New Appointment</h1>
            <p class="text-gray-600 text-sm mb-6">
                Choose a service, faculty member, and a time that works for you.
            </p>

            @if ($errors->any())
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-800 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('appointments.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Service -->
                <div>
                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Service
                    </label>
                    <select id="service_id" name="service_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Select a service</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Faculty -->
                <div>
                    <label for="faculty_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Faculty Member
                    </label>
                    <select id="faculty_id" name="faculty_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Select a faculty member</option>
                        @foreach ($faculty as $member)
                            <option value="{{ $member->id }}" @selected(old('faculty_id') == $member->id)>
                                {{ $member->name }} ({{ $member->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                        Date
                    </label>
                    <input type="date" id="date" name="date"
                           value="{{ old('date', now()->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                        Start Time
                    </label>
                    <input type="time" id="start_time" name="start_time"
                           value="{{ old('start_time', '10:00') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <p class="mt-1 text-xs text-gray-500">
                        24-hour format, e.g. 10:00 or 14:30
                    </p>
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">
                        Duration (minutes)
                    </label>
                    <input type="number" id="duration" name="duration" min="15" max="240"
                           value="{{ old('duration', 30) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                        Reason (optional)
                    </label>
                    <textarea id="reason" name="reason" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                              placeholder="Briefly describe what you want to discuss.">{{ old('reason') }}</textarea>
                </div>

                <div class="pt-4 flex justify-between">
                    <a href="{{ route('appointments.index') }}"
                       class="text-sm text-gray-600 hover:text-gray-800">
                        ← Back to my appointments
                    </a>
                    <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 font-medium">
                        Book Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection




