<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;



Route::get('/', function () {
    $user = Auth::user();
    return view('dashboard', compact('user'));
})->name("dashboard");

Route::get("/login",function(){
    return view("login");
});

Route::post("/login",[UserController::class, 'login'])->name("user.login");


Route::get("/signup",function(){
    return view("signup");
});
Route::post("/signup",[UserController::class, 'signup'])->name("user.signup");


Route::fallback(function(){
    return "Nothing here";
});
