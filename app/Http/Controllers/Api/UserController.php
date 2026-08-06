<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        return User::whereIn('type', ['AM', 'PM'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:AM,PM',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:6'
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        
        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        if ($request->has('password') && count($request->all()) == 1) {
            // For reset password
            $request->validate(['password' => 'required|string|min:6']);
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['message' => 'Password reset successfully']);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:AM,PM',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
        ]);
        
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
