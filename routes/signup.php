<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get("/signup",function(){
    return view("signup");
});
Route::post("/signup",[AuthController::class, 'signup'])->name("user.signup");