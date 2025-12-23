<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleController;
// Home page route
Route::get('/', function () {
    return view('home');
});

// Everything inside here requires login
Route::middleware('auth')->group(function () {

    // ✅ Dashboard: dynamic by role (admin/faculty/student)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Faculty office hours management
    Route::post('/dashboard/faculty-office-hours', [DashboardController::class, 'storeFacultyOfficeHours'])->name('dashboard.faculty-office-hours.store');
    Route::delete('/dashboard/faculty-office-hours/{availabilityRule}', [DashboardController::class, 'deleteFacultyOfficeHours'])->name('dashboard.faculty-office-hours.destroy');

    // ✅ Admin-only actions handled inside controller (no middleware needed)
    // DashboardController already blocks non-admin with abort(403)
    Route::post('/admin/settings/update', [DashboardController::class, 'updateSetting'])->name('admin.settings.update');
    Route::delete('/admin/settings/delete/{id}', [DashboardController::class, 'deleteSetting'])->name('admin.settings.delete');
    Route::post('/admin/services/toggle', [DashboardController::class, 'toggleService'])->name('admin.services.toggle');

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

// Auth routes file
Route::prefix('auth')->group(base_path('routes/auth.php'));

// Admin user management routes (require authentication)
Route::middleware('auth')->group(function () {
    // Create user (POST from dashboard form)
    Route::post('/admin/users/create', [UserController::class, 'store'])->name('admin.users.create');
    
    // Change user type (POST from dashboard form)
    Route::post('/admin/users/changeType/{id}', [UserController::class, 'adminChangeType'])->name('admin.users.changeType');
    
    // Delete user (POST from dashboard form)
    Route::post('/admin/users/delete/{id}', [UserController::class, 'adminDelete'])->name('admin.users.delete');
    
    // Additional routes for future use
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create.get');
    Route::get('/admin/users/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/users/edit/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::get('/admin/users/delete/{id}', [UserController::class, 'delete'])->name('admin.users.delete.get');
    Route::get('/admin/users/show/{id}', [UserController::class, 'show'])->name('admin.users.show');
});

// Google Calendar routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
    Route::delete('/google/disconnect', [GoogleController::class, 'disconnect'])->name('google.disconnect');
});

Route::fallback(function () {
    return "Nothing here";
});
