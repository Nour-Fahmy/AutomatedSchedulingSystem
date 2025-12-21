<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function register(array $data)
    {
        // Signup always creates users as 'student' type
        // Other user types (admin/faculty) should be created through seeders or admin panel
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'type' => 'student', // Always 'student' for signup
        ]);
    }

    public function login(array $credentials)
    {
        
        return Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ]);
    }

    public function logout()
    {
        Auth::logout();
    }
}
