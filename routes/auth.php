<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get("/signup",[AuthController::class, 'showSignup']);
Route::post("/signup",[AuthController::class, 'register'])->name("user.signup");


Route::get("/logout",[AuthController::class, 'logout']);


Route::get("/login",[AuthController::class, 'showLogin']);
Route::post("/login",[AuthController::class, 'login'])->name("user.login");