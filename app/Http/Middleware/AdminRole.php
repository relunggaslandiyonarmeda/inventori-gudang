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
     * Check if user has admin role OR has menu permission for the requested route
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Admin has full access
        if (Session::get('user_role') === 'admin') {
            return $next($request);
        }
        
        // For non-admin users, check menu_permissions
        $userRole = Session::get('user_role');
        if ($userRole !== 'admin') {
            // Get the current route path to determine which menu permission is needed
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
                    $permissions = Session::get('user_menu_permissions', []);
                    if (!in_array($permission, $permissions)) {
                        return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman tersebut.');
                    }
                    break;
                }
            }
        }
        
        return $next($request);
    }
}