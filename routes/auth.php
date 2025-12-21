<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get("/signup",[AuthController::class, 'showSignup']);
Route::post("/signup",[AuthController::class, 'register'])->name("user.signup");


Route::get("/logout",[AuthController::class, 'logout']);


Route::get("/login",[AuthController::class, 'showLogin']);
Route::post("/login",[AuthController::class, 'login'])->name("user.login");

Route::get("/forgot-password", [AuthController::class, 'showForgotPassword']);
Route::post("/forgot-password", [AuthController::class, 'sendResetLink'])->name("password.email");

Route::get("/reset-password/{token}", [AuthController::class, 'showResetForm'])->name("password.reset");
Route::post("/reset-password", [AuthController::class, 'resetPassword'])->name("password.update");