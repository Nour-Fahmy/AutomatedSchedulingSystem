@extends('layouts.layout')

@section('title', 'My Appointments - AlignUp')

@section('content')
<div class="bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Appointments</h1>
                <p class="text-gray-600 text-sm">View and manage your upcoming and past appointments.</p>
            </div>
            <a href="{{ route('appointments.create') }}"
               class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium shadow">
                <i class="fas fa-plus-circle mr-2"></i> Book Appointment
            </a>
        </div>

        @if (session('status'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-800 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @error('appointment')
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-800 text-sm">
                {{ $message }}
            </div>
        @enderror

        @if ($appointments->isEmpty())
            <div class="bg-white rounded-xl shadow p-8 text-center">
                <p class="text-gray-600 mb-4">You don't have any appointments yet.</p>
                <a href="{{ route('appointments.create') }}"
                   class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium shadow">
                    <i class="fas fa-plus-circle mr-2"></i> Book your first appointment
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($appointments as $appointment)
                    @php
                        $isPast = $appointment->start_at < now();
                    @endphp
                    <div class="bg-white rounded-xl shadow p-4 flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-500 mb-1">
                                {{ \Carbon\Carbon::parse($appointment->start_at)->format('D, M j \\a\\t g:i A') }}
                                –
                                {{ \Carbon\Carbon::parse($appointment->end_at)->format('g:i A') }}
                            </div>
                            <div class="font-semibold text-gray-900">
                                {{ $appointment->service->name ?? 'Service' }} with
                                {{ $appointment->faculty->name ?? 'Faculty' }}
                            </div>
                            @if ($appointment->reason)
                                <div class="text-xs text-gray-500 mt-1">
                                    Reason: {{ $appointment->reason }}
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if ($appointment->status === 'confirmed') bg-indigo-100 text-indigo-700
                                @elseif ($appointment->status === 'completed') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($appointment->status) }}
                            </span>

                            @if ($appointment->status === 'confirmed' && !$isPast)
                                <form action="{{ route('appointments.cancel', $appointment) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="text-xs text-red-600 hover:text-red-800 font-medium">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection




