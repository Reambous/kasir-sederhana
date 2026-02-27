<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/');
        }

        // 2. Jika dia adalah 'admin', langsung loloskan tanpa banyak tanya!
        if (Auth::user()->role === 'admin') {
            return $next($request);
        }

        // 3. Jika bukan admin, cek apakah role-nya sesuai dengan rute yang diminta
        if (Auth::user()->role !== $role) {
            abort(403, 'Akses Ditolak. Halaman ini bukan untuk Role Anda.');
        }

        return $next($request);
    }
}
