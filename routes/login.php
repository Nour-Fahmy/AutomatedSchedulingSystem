<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get("/login",function(){
    return view("login");
});

Route::post("/login",[AuthController::class, 'login'])->name("user.login");
