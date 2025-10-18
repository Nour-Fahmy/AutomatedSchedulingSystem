<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


// Route::get("/logout",[UserController::class , "logout"]);
Route::get("/logout",function(){
    session()->flush();
    return redirect("/");
});
