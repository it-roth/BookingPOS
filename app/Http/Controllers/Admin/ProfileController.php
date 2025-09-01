<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use App\Models\Admin;

class ProfileController extends Controller
{
    public function show()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.show', compact('admin'));
    }

    public function settings()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.settings', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:admins,username,' . $admin->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        try {
            // Verify current password if changing password
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $admin->password)) {
                    return back()->withErrors(['current_password' => 'The current password is incorrect.']);
                }
            }

            // Update basic info
            Admin::where('id', $admin->id)->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email
            ]);

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Delete old image if exists
                if ($admin->profile_image && file_exists(public_path($admin->profile_image))) {
                    unlink(public_path($admin->profile_image));
                }
                
                $file->move(public_path('profile-images'), $filename);
                Admin::where('id', $admin->id)->update(['profile_image' => 'profile-images/' . $filename]);
            }

            // Update password if provided
            if ($request->filled('new_password')) {
                Admin::where('id', $admin->id)->update([
                    'password' => Hash::make($request->new_password)
                ]);
            }

            return back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    public function updateImage(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'profile_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        try {
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Delete old image if exists
                if ($admin->profile_image && file_exists(public_path($admin->profile_image))) {
                    unlink(public_path($admin->profile_image));
                }
                
                $file->move(public_path('profile-images'), $filename);
                
                $updated = DB::table('admins')
                    ->where('id', $admin->id)
                    ->update(['profile_image' => 'profile-images/' . $filename]);

                if (!$updated) {
                    throw new \Exception('Failed to update profile image');
                }

                return back()->with('success', 'Profile image updated successfully.');
            }

            return back()->with('error', 'No image file was uploaded.');
        } catch (\Exception $e) {
            Log::error('Profile image update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update profile image. Please try again.');
        }
    }
} 