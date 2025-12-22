<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testRegister(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password1!',
        ];

        $service = new AuthService();
        $result = $service->register($data);

        $this->assertNotNull($result);
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($data['name'], $result->name);
        $this->assertEquals($data['email'], $result->email);
        $this->assertEquals('student', $result->type);
        $this->assertNotEquals($data['password'], $result->password); // Password should be hashed
        $this->assertTrue(Hash::check($data['password'], $result->password)); // Verify password is hashed correctly
    }

    public function testLogin(): void
    {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'Password1!',
        ];

        Auth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn(true);

        $service = new AuthService();

        $this->assertTrue($service->login($credentials));
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'WrongPassword1!',
        ];

        Auth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn(false);

        $service = new AuthService();

        $this->assertFalse($service->login($credentials));
    }

    public function testLogout(): void
    {
        Auth::shouldReceive('logout')
            ->once();

        $service = new AuthService();
        $service->logout();

        // Assert that logout was called (verified by shouldReceive expectation)
        $this->assertTrue(true);
    }
}

