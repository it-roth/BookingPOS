<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        return view('dashboard.users.form');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'role' => ['required', 'string', 'in:user,admin'],
                'is_active' => ['required', 'boolean'],
                'password' => ['required', 'confirmed', Password::defaults()],
                'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            $data = $request->except(['password', 'password_confirmation', 'profile_image']);
            $data['password'] = Hash::make($request->password);

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                
                if (!$file->isValid()) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['profile_image' => 'Invalid image file']);
                }

                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('profile-images'), $filename);
                $data['profile_image'] = 'profile-images/' . $filename;
            }

            User::create($data);

            return redirect()->route('dashboard.users.index')
                ->with('success', 'User created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error creating user: ' . $e->getMessage()]);
        }
    }

    public function edit(User $user)
    {
        return view('dashboard.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'role' => ['required', 'string', 'in:user,admin'],
                'is_active' => ['required', 'boolean'],
                'password' => ['nullable', 'confirmed', Password::defaults()],
                'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            $data = $request->except(['password', 'password_confirmation', 'profile_image']);

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                
                if (!$file->isValid()) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['profile_image' => 'Invalid image file']);
                }

                // Delete old image if exists
                if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                    unlink(public_path($user->profile_image));
                }

                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('profile-images'), $filename);
                $data['profile_image'] = 'profile-images/' . $filename;
            }

            $user->update($data);

            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            return redirect()->route('dashboard.users.index')
                ->with('success', 'User updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error updating user: ' . $e->getMessage()]);
        }
    }

    public function destroy(User $user)
    {
        try {
            Log::info('UserController: Attempting to delete user: ' . $user->id);

            // Prevent admin from deleting themselves
            $currentAdmin = auth('admin')->user();
            if ($currentAdmin && $currentAdmin->email === $user->email) {
                Log::warning('UserController: Admin attempted to delete themselves: ' . $user->id);
                return back()->withErrors(['error' => 'You cannot delete your own account.']);
            }

            // Delete profile image if exists
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                Log::info('UserController: Deleting profile image: ' . $user->profile_image);
                unlink(public_path($user->profile_image));
            }

            $user->delete();
            Log::info('UserController: User deleted successfully: ' . $user->id);

            return redirect()->route('dashboard.users.index')
                ->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            Log::error('UserController: Failed to delete user: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to delete user: ' . $e->getMessage()]);
        }
    }
} 