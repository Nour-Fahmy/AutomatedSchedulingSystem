<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Show login form
    public function showLogin()
    {
        return view('login');
    }

    // Show signup form
    public function showSignup()
    {
        return view('signup');
    }

    // Handle registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required','string','min:6','regex:/[A-Z]/','regex:/[a-z]/','regex:/[0-9]/','regex:/[@$!%*?&#]/','confirmed'],


            'type' => 'required|in:student,faculty,admin',
        ]);

        $this->authService->register($validated);
        return redirect('/auth/login')->with('success', 'Account created successfully!');

        // var_dump($validated);
    }

    // Handle login
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // var_dump($credentials);

        if ($this->authService->login($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password']);
    }

    // Logout
    public function logout(Request $request)
    {
        $this->authService->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth/login');
    }

    // Show forgot password form
    public function showForgotPassword()
    {
        return view('forgot-password');
    }

    // Send reset link email if the user exists
    public function sendResetLink(Request $request)
    {
        // Validate email format first
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        // Check if email exists in database
        try {
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                return back()->withErrors(['email' => 'We could not find a user with that email address.']);
            }
        } catch (\Exception $e) {
            // Database connection error - show helpful message
            return back()->withErrors(['email' => 'Database connection error. Please check your database configuration.']);
        }

        // Send reset link
        try {
            $status = Password::sendResetLink(['email' => $email]);

            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => 'Password reset link has been sent to your email address.'])
                : back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            // Mail sending error
            return back()->withErrors(['email' => 'Failed to send reset link. Please check your mail configuration.']);
        }
    }

    // Show reset password form
    public function showResetForm(Request $request, string $token)
    {
        return view('reset-password', [
            'request' => $request,
            'token' => $token,
        ]);
    }

    // Handle password reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => ['required','string','min:6','confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('user.login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
