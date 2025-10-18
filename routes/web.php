<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;



Route::get('/', function () {
    return view('dashboard');
})->name("dashboard");

Route::get("/login",function(){
    return view("login");
});

Route::post("/login",[UserController::class, 'login'])->name("user.login");


Route::get("/signup",function(){
    return view("signup");
});
Route::post("/signup",[UserController::class, 'signup'])->name("user.signup");

// Route::get("/logout",[UserController::class , "logout"]);
Route::get("/logout",function(){
    session()->flush();
    return redirect("/");
});


Route::fallback(function(){
    return "Nothing here";
});
