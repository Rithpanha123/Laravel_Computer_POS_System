<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Gender;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role', 'gender']);

        // Search by username, full name, email, or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Filter by active status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->latest('created_at')->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    ///create a new user

    public function create()
    {
        $roles = Role::all();
        $genders = Gender::all();

        return view('users.create', compact('roles', 'genders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'       => 'required|string|max:255',
            'username'        => 'required|string|max:100|unique:users,username',
            'email'           => 'required|email|unique:users,email',
            'phone'           => 'nullable|string|max:20',
            'password'        => 'required|string|min:6|confirmed',
            'role_id'         => 'required|exists:roles,role_id',
            'gender_id'       => 'required|exists:genders,gender_id',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'       => 'nullable|boolean',
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        $validated['password_hash'] = Hash::make($request->password);
        $validated['is_active'] = $request->boolean('is_active');

        User::create($validated);
        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    //edit user

    public function edit(User $user)
    {
        $roles = Role::all();
        $genders = Gender::all();

        return view('users.edit', compact('user', 'roles', 'genders'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'full_name'       => 'required|string|max:255',
            'username'        => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($user->user_id, 'user_id')],
            'email'           => ['required', 'email', Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')],
            'phone'           => 'nullable|string|max:20',
            'password'        => 'nullable|string|min:6|confirmed', // optional when editing
            'role_id'         => 'required|exists:roles,role_id',
            'gender_id'       => 'required|exists:genders,gender_id',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'       => 'nullable|boolean',
        ]);

        // Handle profile picture upload & delete old file
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        // Only update password if provided
        if (!empty($request->password)) {
            $validated['password_hash'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    //delete user

    public function destroy(User $user)
    {
        // Prevent deleting the currently authenticated user
        if (Auth::user()->user_id === $user->user_id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account while logged in.');
        }

        // Delete profile picture from storage if it exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Delete user record from database
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}