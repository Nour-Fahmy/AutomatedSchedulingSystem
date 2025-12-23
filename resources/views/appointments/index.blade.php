@extends('layouts.layout')

@section('title', 'My Appointments - AlignUp')

@section('content')
<div class="bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">My Appointments</h1>
                <p class="text-gray-600 text-sm flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i>
                    View and manage your upcoming and past appointments.
                </p>
            </div>
            @if(!isset($user) || $user->isStudent())
                <a href="{{ route('appointments.create') }}"
                   class="inline-flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-indigo-700 hover:to-purple-700 text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-plus-circle mr-2"></i> Book Appointment
                </a>
            @endif
        </div>

        @if (session('status'))
            <div class="mb-6 px-5 py-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 text-sm shadow-md animate-pulse">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 text-green-600"></i>
                    {{ session('status') }}
                </div>
            </div>
        @endif

        @error('appointment')
            <div class="mb-6 px-5 py-4 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 text-sm shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2 text-red-600"></i>
                    {{ $message }}
                </div>
            </div>
        @enderror

        @if ($appointments->isEmpty())
            <div class="bg-gradient-to-br from-white to-indigo-50 rounded-2xl shadow-xl p-12 text-center border-2 border-indigo-200">
                <div class="mb-6">
                    <i class="fas fa-calendar-times text-6xl text-indigo-300 mb-4"></i>
                </div>
                <p class="text-gray-700 text-lg mb-6 font-medium">You don't have any appointments yet.</p>
                @if(!isset($user) || $user->isStudent())
                    <a href="{{ route('appointments.create') }}"
                       class="inline-flex items-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-plus-circle mr-2"></i> Book your first appointment
                    </a>
                @endif
            </div>
        @else
            <div class="space-y-4">
                @foreach ($appointments as $appointment)
                    @php
                        $isPast = $appointment->start_at < now();
                        $statusColors = [
                            'confirmed' => 'from-blue-500 to-indigo-500',
                            'completed' => 'from-green-500 to-emerald-500',
                            'canceled' => 'from-red-500 to-rose-500',
                            'no-show' => 'from-orange-500 to-amber-500'
                        ];
                        $statusColor = $statusColors[$appointment->status] ?? 'from-gray-500 to-gray-600';
                    @endphp
                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-md hover:shadow-xl p-5 flex items-center justify-between border-2 border-gray-200 hover:border-indigo-300 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                                    <i class="fas fa-calendar-check text-indigo-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-indigo-600 mb-1">
                                        <i class="far fa-clock mr-2"></i>
                                        {{ \Carbon\Carbon::parse($appointment->start_at)->format('D, M j \\a\\t g:i A') }}
                                        –
                                        {{ \Carbon\Carbon::parse($appointment->end_at)->format('g:i A') }}
                                    </div>
                                    <div class="font-bold text-lg text-gray-900 flex items-center">
                                        <i class="fas fa-briefcase text-gray-400 mr-2 text-sm"></i>
                                        {{ $appointment->service->name ?? 'Service' }} 
                                        @if(isset($user) && $user->isStudent())
                                            <span class="text-gray-500 mx-2">with</span>
                                            <span class="text-indigo-600">{{ $appointment->faculty->name ?? 'Faculty' }}</span>
                                        @elseif(isset($user) && $user->isFaculty())
                                            <span class="text-gray-500 mx-2">with</span>
                                            <span class="text-indigo-600">{{ $appointment->student->name ?? 'Student' }}</span>
                                        @else
                                            <span class="text-gray-500 mx-2">with</span>
                                            <span class="text-indigo-600">{{ $appointment->faculty->name ?? 'Faculty' }}</span>
                                        @endif
                                    </div>
                                    @if ($appointment->reason)
                                        <div class="text-xs text-gray-500 mt-2 flex items-center bg-gray-100 rounded px-3 py-1.5 inline-block">
                                            <i class="fas fa-sticky-note mr-2"></i>
                                            {{ $appointment->reason }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 ml-4">
                            <span class="px-4 py-2 rounded-full text-xs font-bold bg-gradient-to-r {{ $statusColor }} text-white shadow-md">
                                {{ ucfirst($appointment->status) }}
                            </span>

                            @if ($appointment->status === 'confirmed' && !$isPast)
                                <form action="{{ route('appointments.cancel', $appointment) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Are you sure you want to cancel this appointment?')"
                                            class="px-4 py-2 bg-gradient-to-r from-red-500 to-rose-500 text-white rounded-lg hover:from-red-600 hover:to-rose-600 font-semibold text-xs shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center">
                                        <i class="fas fa-times mr-1"></i>
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




