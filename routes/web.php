<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ForumController;

// Home page route
Route::get('/', function () {
    return view('home');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        // Get real counts
        $upcomingAppointments = \App\Models\Appointment::where('student_id', $user->id)
            ->where('status', 'confirmed')
            ->where('start_at', '>', now())
            ->count();
        
        $activeServices = \App\Models\Service::where('is_active', true)->count();
        
        $activeThreads = \App\Models\ForumThread::where('is_locked', false)
            ->where('created_at', '>', now()->subDays(7))
            ->count();
        
        return view('dashboard', compact('upcomingAppointments', 'activeServices', 'activeThreads'));
    })->name('dashboard');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Appointments (student)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

    // Forum
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::get('/forum/{id}', [ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum/{id}/reply', [ForumController::class, 'reply'])->name('forum.reply');
});

Route::prefix('auth')->group(base_path('routes/auth.php'));

Route::fallback(function(){
    return "Nothing here";
});
