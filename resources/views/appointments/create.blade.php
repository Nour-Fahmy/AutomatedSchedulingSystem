@extends('layouts.layout')

@section('title', 'Book Appointment - AlignUp')

@section('content')
<div class="bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-white to-indigo-50 rounded-2xl shadow-2xl p-8 border-2 border-indigo-200">
            <div class="mb-6">
                <div class="flex items-center mb-3">
                    <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl mr-4 shadow-lg">
                        <i class="fas fa-calendar-plus text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Submit Your Availability</h1>
                        <p class="text-gray-600 text-sm mt-1">
                            Select a service type and provide your available days and time range. The system will automatically find a matching slot with a faculty member.
                        </p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 px-5 py-4 rounded-xl bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 text-sm shadow-md animate-pulse">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Please fix the following errors:</strong>
                    </div>
                    <ul class="list-disc list-inside ml-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('appointments.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Service Type -->
                <div class="transform transition-all duration-200 hover:scale-[1.02]">
                    <label for="service_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-briefcase text-indigo-500 mr-2"></i>
                        Service Type <span class="text-red-500 ml-1">*</span>
                    </label>
                    <select id="service_id" name="service_id" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-indigo-400 bg-white shadow-sm">
                        <option value="">Select a service type</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Available Days -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-calendar-week text-indigo-500 mr-2"></i>
                        Available Days <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @php
                            $weekdays = [
                                0 => ['name' => 'Sunday', 'icon' => 'fa-sun', 'color' => 'from-yellow-400 to-orange-500'],
                                1 => ['name' => 'Monday', 'icon' => 'fa-moon', 'color' => 'from-blue-400 to-indigo-500'],
                                2 => ['name' => 'Tuesday', 'icon' => 'fa-star', 'color' => 'from-pink-400 to-rose-500'],
                                3 => ['name' => 'Wednesday', 'icon' => 'fa-fire', 'color' => 'from-orange-400 to-red-500'],
                                4 => ['name' => 'Thursday', 'icon' => 'fa-bolt', 'color' => 'from-purple-400 to-indigo-500'],
                                5 => ['name' => 'Friday', 'icon' => 'fa-heart', 'color' => 'from-green-400 to-emerald-500'],
                                6 => ['name' => 'Saturday', 'icon' => 'fa-gem', 'color' => 'from-teal-400 to-cyan-500']
                            ];
                            $oldDays = old('available_days', []);
                        @endphp
                        @foreach ($weekdays as $dayNum => $day)
                            <label class="group relative flex items-center space-x-2 p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-indigo-400 bg-white hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 transition-all duration-200 transform hover:scale-105 hover:shadow-md">
                                <input type="checkbox" name="available_days[]" value="{{ $dayNum }}"
                                       @checked(in_array($dayNum, $oldDays))
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5 transform group-hover:scale-110 transition-transform">
                                <div class="flex items-center flex-1">
                                    <i class="fas {{ $day['icon'] }} text-gray-400 group-hover:text-indigo-500 mr-2 transition-colors"></i>
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700 transition-colors">{{ $day['name'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-3 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Select all days when you are available for this service.
                    </p>
                </div>

                <!-- Time Range -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="transform transition-all duration-200 hover:scale-[1.02]">
                        <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-clock text-indigo-500 mr-2"></i>
                            Available From <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="time" id="start_time" name="start_time" required
                               value="{{ old('start_time', '09:00') }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-indigo-400 bg-white shadow-sm">
                    </div>
                    <div class="transform transition-all duration-200 hover:scale-[1.02]">
                        <label for="end_time" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-clock text-indigo-500 mr-2"></i>
                            Available Until <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="time" id="end_time" name="end_time" required
                               value="{{ old('end_time', '17:00') }}"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-indigo-400 bg-white shadow-sm">
                    </div>
                </div>
                <p class="text-xs text-gray-500 flex items-center mt-2">
                    <i class="fas fa-info-circle mr-2"></i>
                    Specify the time range when you are available on the selected days (24-hour format).
                </p>

                <div class="pt-6 flex justify-between items-center border-t-2 border-gray-200 mt-6">
                    <a href="{{ route('appointments.index') }}"
                       class="text-sm text-gray-600 hover:text-indigo-600 font-medium flex items-center transition-colors duration-200 transform hover:scale-105">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to my appointments
                    </a>
                    <button type="submit"
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection




