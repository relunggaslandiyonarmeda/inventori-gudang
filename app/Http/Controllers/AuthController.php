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

        // Login admin hardcoded (username: admin, password: admin)
        if ($request->username === 'admin' && $request->password === 'admin') {
            Session::put('admin_logged_in', true);
            Session::put('admin_username', 'admin');
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Username atau password salah!');
    }

    /**
     * Logout admin
     */
    public function logout()
    {
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
