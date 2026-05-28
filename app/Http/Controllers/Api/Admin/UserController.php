<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

// Admin-only user management with guards against self lockout.
class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->get();

        return response()->json([
            'users' => $users,
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'user' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $isSelf = $user->id === $request->user()->id;

        // can't deactivate yourself
        if ($isSelf && $request->has('is_active') && $request->boolean('is_active') === false) {
            return response()->json([
                'message' => 'You cannot deactivate your own account.',
            ], 422);
        }

        // can't change your own role either
        if ($isSelf && $data['role'] !== $user->role) {
            return response()->json([
                'message' => 'You cannot change your own role.',
            ], 422);
        }

        $data['is_active'] = $request->boolean('is_active', $user->is_active);

        $user->update($data);

        return response()->json([
            'message' => 'User updated.',
            'user'    => $user,
        ]);
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => 'You cannot delete your own account.',
            ], 422);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted.']);
    }
}
