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
            Session::put('user_profile_photo', null);
            Session::put('user_menu_permissions', ['master_barang', 'barang_masuk', 'barang_keluar', 'barang_retur', 'barang_rusak']);
            
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
            Session::put('user_profile_photo', $user->profile_photo);
            Session::put('user_menu_permissions', is_array($user->menu_permissions) ? $user->menu_permissions : []);
            
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
