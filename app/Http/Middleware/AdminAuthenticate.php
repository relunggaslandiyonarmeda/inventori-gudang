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
        if (Session::get('user_logged_in')) {
            return $next($request);
        }
        
        // Check if "remember me" cookie exists
        $remember = $request->cookie('user_remember');
        if ($remember) {
            // Restore session
            Session::put('user_logged_in', true);
            
            // Restore admin session
            if ($remember === 'admin') {
                Session::put('user_id', 'admin');
                Session::put('user_name', 'Administrator');
                Session::put('user_username', 'admin');
                Session::put('user_role', 'admin');
            } else {
                // Restore database user session
                $user = \App\Models\User::find($remember);
                if ($user) {
                    Session::put('user_id', $user->id);
                    Session::put('user_name', $user->name);
                    Session::put('user_username', $user->username);
                    Session::put('user_role', $user->role);
                }
            }
            return $next($request);
        }

        return redirect()->route('login');
    }
}
