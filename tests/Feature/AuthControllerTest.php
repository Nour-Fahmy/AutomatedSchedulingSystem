<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;
use App\Http\Controllers\AuthController;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShowLogin(): void
    {
        $response = $this->get('/auth/login');

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    public function testShowSignup(): void
    {
        $response = $this->get('/auth/signup');

        $response->assertStatus(200);
        $response->assertViewIs('signup');
    }

    public function testRegister(): void
    {
        $response = $this->post('/auth/signup', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $response->assertRedirect('/auth/login');
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'type' => 'student',
        ]);
    }

    public function testLogin(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Password1!'),
        ]);

        $response = $this->post('/auth/login', [
            'email' => $user->email,
            'password' => 'Password1!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Password1!'),
        ]);

        $response = $this->from('/auth/login')->post('/auth/login', [
            'email' => $user->email,
            'password' => 'WrongPassword1!',
        ]);

        $response->assertRedirect('/auth/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function testLogout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/auth/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function testShowForgotPassword(): void
    {
        $response = $this->get('/auth/forgot-password');

        $response->assertStatus(200);
        $response->assertViewIs('forgot-password');
    }

    public function testSendResetLink(): void
    {
        $user = User::factory()->create();

        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => $user->email])
            ->andReturn(Password::RESET_LINK_SENT);

        $response = $this->post('/auth/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertSessionHas('status');
    }

    public function testSendResetLinkWithNonExistingUser(): void
    {
        $response = $this->from('/auth/forgot-password')->post('/auth/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertRedirect('/auth/forgot-password');
        $response->assertSessionHasErrors('email');
    }

    public function testShowResetForm(): void
    {
        $token = 'dummy-token';
        $email = 'test@example.com';

        $response = $this->get("/auth/reset-password/{$token}?email={$email}");

        $response->assertStatus(200);
        $response->assertViewIs('reset-password');
        $response->assertViewHasAll([
            'token',
            'request',
            'email',
        ]);
    }
}

