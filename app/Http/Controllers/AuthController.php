<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login - supports both admin and database users
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // ===== DATABASE USER LOGIN =====
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        // ===== ADMIN LOGIN (hardcoded fallback for default admin) =====
        // Check if trying to login as admin with default password
        $adminUser = User::where('username', 'admin')->withTrashed()->first();
        if (!$adminUser && $credentials['username'] === 'admin' && $credentials['password'] === 'admin123') {
            // Create default admin user if not exists
            $adminUser = User::create([
                'name' => 'Administrator',
                'email' => 'admin@inventori.local',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
            Auth::login($adminUser, $request->filled('remember'));
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Username atau password salah!');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
