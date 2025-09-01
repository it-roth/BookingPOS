<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status'));
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        return view('dashboard.users.create');
    }

    public function store(Request $request)
    {
        try {
            // Log the incoming request data
            Log::info('Creating new user with data:', $request->except(['password', 'password_confirmation']));

            // Validate request
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'required|string|in:user,admin',
                'is_active' => 'required|boolean',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            Log::info('Validation passed, creating user...');

            // Convert is_active to boolean
            $validated['is_active'] = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                Log::info('Handling profile image upload...');
                $file = $request->file('profile_image');
                $filename = time() . '_' . $file->getClientOriginalName();

                // Create the directory if it doesn't exist
                $uploadPath = public_path('profile-images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $filename);
                $validated['profile_image'] = 'profile-images/' . $filename;
            }

            // Remove password_confirmation from validated data
            unset($validated['password_confirmation']);

            // Create user using mass assignment (User model will handle password hashing)
            $user = User::create($validated);

            Log::info('User created successfully:', ['user_id' => $user->id]);

            return redirect()
                ->route('dashboard.users.index')
                ->with('success', 'User created successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed:', [
                'errors' => $e->errors(),
                'data' => $request->except(['password', 'password_confirmation'])
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to create user:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->except(['password', 'password_confirmation'])
            ]);

            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['error' => 'Failed to create user: ' . $e->getMessage()]);
        }
    }

    public function edit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        try {
            Log::info('Updating user: ' . $user->id, $request->except(['password', 'password_confirmation']));

            $rules = [
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'role' => 'required|string|in:user,admin',
                'is_active' => 'required|boolean',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ];

            if ($request->filled('password')) {
                $rules['password'] = 'required|string|min:6|confirmed';
            }

            $validated = $request->validate($rules);

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Delete old image if exists
                if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                    unlink(public_path($user->profile_image));
                }
                
                // Create the directory if it doesn't exist
                $uploadPath = public_path('profile-images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $file->move($uploadPath, $filename);
                $validated['profile_image'] = 'profile-images/' . $filename;
            }

            // Update user with explicit field assignment
            $user->username = $validated['username'];
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
            $user->role = $validated['role'];
            $user->is_active = $validated['is_active'];
            if (isset($validated['profile_image'])) {
                $user->profile_image = $validated['profile_image'];
            }
            $user->save();

            return redirect()
                ->route('dashboard.users.index')
                ->with('success', 'User updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed during user update:', [
                'errors' => $e->errors(),
                'user_id' => $user->id
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to update user: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['error' => 'Failed to update user: ' . $e->getMessage()]);
        }
    }

    public function destroy(User $user)
    {
        try {
            Log::info('Attempting to delete user: ' . $user->id);

            // Prevent admin from deleting themselves
            $currentAdmin = auth('admin')->user();
            if ($currentAdmin && $currentAdmin->email === $user->email) {
                Log::warning('Admin attempted to delete themselves: ' . $user->id);
                return back()->withErrors(['error' => 'You cannot delete your own account.']);
            }

            // Delete profile image if exists
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                Log::info('Deleting profile image: ' . $user->profile_image);
                unlink(public_path($user->profile_image));
            }

            $user->delete();
            Log::info('User deleted successfully: ' . $user->id);

            return redirect()
                ->route('dashboard.users.index')
                ->with('success', 'User deleted successfully');

        } catch (\Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to delete user: ' . $e->getMessage()]);
        }
    }

    public function show(User $user)
    {
        return view('dashboard.users.show', compact('user'));
    }
} 