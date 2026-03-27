<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // ===== ADMIN LOGIN (hardcoded) =====
        if ($username === 'admin' && $password === 'admin123') {
            $request->session()->regenerate();
            
            Session::put('user_logged_in', true);
            Session::put('user_id', 'admin');
            Session::put('user_name', 'Administrator');
            Session::put('user_username', 'admin');
            Session::put('user_role', 'admin');
            
            if ($request->has('remember')) {
                cookie()->queue('user_remember', 'admin', 525600);
            }
            
            return redirect()->route('dashboard');
        }

        // ===== DATABASE USER LOGIN =====
        $user = User::where('username', $username)->first();
        
        if ($user && Hash::check($password, $user->password)) {
            $request->session()->regenerate();
            
            Session::put('user_logged_in', true);
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            Session::put('user_username', $user->username);
            Session::put('user_role', $user->role);
            
            if ($request->has('remember')) {
                cookie()->queue('user_remember', $user->id, 525600);
            }
            
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Username atau password salah!');
    }

    /**
     * Logout user
     */
    public function logout()
    {
        cookie()->queue(cookie()->forget('user_remember'));
        
        Session::flush();
        return redirect()->route('login');
    }

    /**
     * Cek apakah user sudah login
     */
    public function checkAuth()
    {
        return Session::get('user_logged_in', false);
    }
}
