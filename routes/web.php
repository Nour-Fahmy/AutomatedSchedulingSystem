<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Home page route
Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::prefix('auth')->group(base_path('routes/auth.php'));

Route::fallback(function(){
    return "Nothing here";
});
