<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in via session
        if (Session::get('admin_logged_in')) {
            return $next($request);
        }
        
        // Check if "remember me" cookie exists
        if ($request->cookie('admin_remember')) {
            // Restore session
            Session::put('admin_logged_in', true);
            Session::put('admin_username', 'admin');
            return $next($request);
        }

        return redirect()->route('login');
    }
}
