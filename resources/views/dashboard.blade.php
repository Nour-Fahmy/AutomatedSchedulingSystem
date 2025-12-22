@extends('layouts.layout')

@section('title', 'Dashboard - AlignUp')

@section('content')
<div class="bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Welcome back, {{ Auth::user()->name }}!
            </h1>
            <p class="text-xl text-gray-600">
                Manage your appointments and academic schedule
            </p>
        </div>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <!-- Upcoming Appointments -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-indigo-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-check text-indigo-600 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-indigo-600">{{ $upcomingAppointments ?? 0 }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Upcoming Appointments</h3>
                <p class="text-gray-600 text-sm">You have {{ $upcomingAppointments ?? 0 }} {{ Str::plural('appointment', $upcomingAppointments ?? 0) }} scheduled</p>
            </div>

            <!-- Available Services -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-green-600 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-green-600">{{ $activeServices ?? 0 }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Available Services</h3>
                <p class="text-gray-600 text-sm">{{ $activeServices ?? 0 }} {{ Str::plural('service', $activeServices ?? 0) }} available for booking</p>
            </div>

            <!-- Forum Discussions -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-comments text-purple-600 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-purple-600">{{ $activeThreads ?? 0 }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Active Discussions</h3>
                <p class="text-gray-600 text-sm">{{ $activeThreads ?? 0 }} {{ Str::plural('thread', $activeThreads ?? 0) }} active this week</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('appointments.create') }}" class="bg-indigo-600 text-white p-4 rounded-lg hover:bg-indigo-700 transition duration-200 text-center">
                    <i class="fas fa-plus-circle text-2xl mb-2"></i>
                    <div class="font-semibold">Book Appointment</div>
                </a>
                <a href="{{ route('appointments.index') }}" class="bg-green-600 text-white p-4 rounded-lg hover:bg-green-700 transition duration-200 text-center">
                    <i class="fas fa-calendar-alt text-2xl mb-2"></i>
                    <div class="font-semibold">View Schedule</div>
                </a>
                <a href="{{ route('forum.index') }}" class="bg-purple-600 text-white p-4 rounded-lg hover:bg-purple-700 transition duration-200 text-center">
                    <i class="fas fa-comments text-2xl mb-2"></i>
                    <div class="font-semibold">Forum</div>
                </a>
                <a href="{{ route('settings.index') }}" class="bg-gray-600 text-white p-4 rounded-lg hover:bg-gray-700 transition duration-200 text-center">
                    <i class="fas fa-cog text-2xl mb-2"></i>
                    <div class="font-semibold">Settings</div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection