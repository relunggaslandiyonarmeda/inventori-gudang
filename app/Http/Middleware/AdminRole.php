<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRole
{
    /**
     * Handle an incoming request.
     * Check if user has admin role OR has menu permission for the requested route
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admin has full access
        if ($user->role === 'admin') {
            return $next($request);
        }

        // For non-admin users, check menu_permissions
        $currentPath = $request->path();

        // Map routes to menu permissions
        $routePermissions = [
            'master-barang' => 'master_barang',
            'barang-retur' => 'barang_retur',
            'barang-rusak' => 'barang_rusak',
            'laporan-rak' => 'laporan_rak',
            'laporan-rusak' => 'laporan_rusak',
            'users' => 'users',
        ];

        // Check each route prefix
        foreach ($routePermissions as $route => $permission) {
            if (str_starts_with($currentPath, $route)) {
                if (!$user->hasMenuAccess($permission)) {
                    return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman tersebut.');
                }
                break;
            }
        }

        return $next($request);
    }
}