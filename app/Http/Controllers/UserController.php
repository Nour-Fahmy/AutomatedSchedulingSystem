<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    private function requireAdmin()
    {
        $u = auth()->user();
        if (!$u || !$u->isAdmin()) abort(403);
        return $u;
    }

    // Alias methods for route compatibility
    public function create()
    {
        // This is just for route compatibility - dashboard handles the form
        $this->requireAdmin();
        return back();
    }

    public function store(Request $request)
    {
        // Alias for adminCreate
        return $this->adminCreate($request);
    }

    public function edit($id)
    {
        $this->requireAdmin();
        $user = User::findOrFail($id);
        return back()->with('editing_user', $user);
    }

    public function update(Request $request, $id)
    {
        // Alias for adminChangeType
        return $this->adminChangeType($request, $id);
    }

    public function delete($id)
    {
        $this->requireAdmin();
        $user = User::findOrFail($id);
        return back()->with('deleting_user', $user);
    }

    public function destroy(Request $request, $id)
    {
        // Alias for adminDelete
        return $this->adminDelete($request, $id);
    }

    public function show($id)
    {
        $this->requireAdmin();
        $user = User::findOrFail($id);
        return back()->with('viewing_user', $user);
    }

    // Original methods (kept for backward compatibility)
    public function adminCreate(Request $request)
    {
        $this->requireAdmin();

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'type' => 'required|in:student,faculty,admin',
                'password' => 'required|string|min:6|max:255',
            ]);

            $this->userService->createUser($validated);

            return back()->with('success', 'User created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('User creation failed: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while creating the user: ' . $e->getMessage())->withInput();
        }
    }

    public function adminChangeType(Request $request, $id)
    {
        $admin = $this->requireAdmin();

        try {
            $validated = $request->validate([
                'type' => 'required|in:student,faculty,admin',
            ]);

            $this->userService->updateUserType($id, $validated['type'], $admin);

            return back()->with('success', 'User role updated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function adminDelete(Request $request, $id)
    {
        $admin = $this->requireAdmin();

        try {
            $this->userService->deleteUser($id, $admin);

            return back()->with('success', 'User deleted.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
