<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
     * Proses login admin
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Login admin using environment variables
        $adminUsername = config('app.admin_username', 'admin');
        $adminPassword = config('app.admin_password', 'admin123');
        
        if ($request->username === $adminUsername && $request->password === $adminPassword) {
            // Regenerate session ID to prevent session fixation attacks
            $request->session()->regenerate();
            
            Session::put('admin_logged_in', true);
            Session::put('admin_username', 'admin');
            
            // If "remember me" is checked, set a long-lived cookie
            if ($request->has('remember')) {
                // Set cookie for 1 year (525600 minutes)
                cookie()->queue('admin_remember', true, 525600);
            }
            
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Username atau password salah!');
    }

    /**
     * Logout admin
     */
    public function logout()
    {
        // Clear the remember me cookie
        cookie()->queue(cookie()->forget('admin_remember'));
        
        Session::flush();
        return redirect()->route('login');
    }

    /**
     * Cek apakah admin sudah login
     */
    public function checkAuth()
    {
        return Session::get('admin_logged_in', false);
    }
}
