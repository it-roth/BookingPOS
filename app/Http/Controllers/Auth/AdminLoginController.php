<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use App\Models\User;

class AdminLoginController extends Controller
{
    /**
     * Show the admin login form
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle an admin or user login attempt
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Try admin login first
        if (Auth::guard('admin')->attempt([
            'username' => $request->username,
            'password' => $request->password,
            'is_active' => true
        ], $request->filled('remember'))) {
            $request->session()->regenerate();
            $admin = Auth::guard('admin')->user();
            
            return redirect()->intended('/dashboard')
                ->with('success', 'Welcome back, ' . $admin->name);
        }

        // If admin login fails, try user login
        if (Auth::guard('web')->attempt([
            'username' => $request->username,
            'password' => $request->password,
            'is_active' => true
        ], $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();
            
            return redirect()->intended('/dashboard/pos')
                ->with('success', 'Welcome back, ' . $user->name);
        }

        return back()
            ->withInput($request->only('username', 'remember'))
            ->withErrors(['username' => 'Invalid credentials or inactive account.']);
    }

    /**
     * Logout the admin or user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Logout from both guards
        Auth::guard('admin')->logout();
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
            ->with('success', 'You have been successfully logged out.');
    }
} 