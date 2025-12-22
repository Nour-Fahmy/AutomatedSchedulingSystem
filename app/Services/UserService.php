<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * Create a new user (admin only)
     */
    public function createUser(array $data): User
    {
        $validated = $this->validateUserData($data);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'type' => $validated['type'],
            'password' => Hash::make($validated['password']),
        ]);

        if (!$user) {
            throw new \Exception('Failed to create user.');
        }

        return $user;
    }

    /**
     * Update user type (admin only)
     */
    public function updateUserType(int $userId, string $newType, User $admin): void
    {
        $this->validateUserType($newType);
        
        $target = User::findOrFail($userId);

        // PREVENT: admin cannot demote himself
        if ($target->id === $admin->id && $newType !== 'admin') {
            throw new \Exception('You cannot change your own admin role.');
        }

        $target->update(['type' => $newType]);
    }

    /**
     * Delete a user (admin only)
     */
    public function deleteUser(int $userId, User $admin): void
    {
        $target = User::findOrFail($userId);

        // PREVENT: admin cannot delete himself
        if ($target->id === $admin->id) {
            throw new \Exception('You cannot delete your own account.');
        }

        // PREVENT: keep at least 1 admin in the system
        if ($target->type === 'admin') {
            $adminCount = User::where('type', 'admin')->count();
            if ($adminCount <= 1) {
                throw new \Exception('Cannot delete the last admin.');
            }
        }

        $target->delete();
    }

    /**
     * Get all users ordered by type and name
     */
    public function getAllUsers()
    {
        return User::orderBy('type')->orderBy('name')->get();
    }

    /**
     * Get user counts by type
     */
    public function getUserCounts(): array
    {
        return [
            'students' => User::where('type', 'student')->count(),
            'faculty'  => User::where('type', 'faculty')->count(),
            'admins'   => User::where('type', 'admin')->count(),
            'total'    => User::count(),
        ];
    }

    /**
     * Validate user creation/update data
     */
    private function validateUserData(array $data): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'type' => 'required|in:student,faculty,admin',
            'password' => 'required|string|min:6|max:255',
        ];

        // If updating, allow email to be the same
        if (isset($data['id'])) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $data['id'];
        }

        return validator($data, $rules)->validate();
    }

    /**
     * Validate user type
     */
    private function validateUserType(string $type): void
    {
        if (!in_array($type, ['student', 'faculty', 'admin'], true)) {
            throw new \InvalidArgumentException('Invalid user type.');
        }
    }
}

