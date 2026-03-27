<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AdminRole
{
    /**
     * Handle an incoming request.
     * Check if user has admin role
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user has admin role
        if (Session::get('user_role') !== 'admin') {
            // Redirect to dashboard for non-admin users
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        }
        
        return $next($request);
    }
}