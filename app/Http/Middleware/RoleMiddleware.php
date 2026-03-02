<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Cek apakah user sudah login, jika belum lempar ke halaman login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. SUPER ADMIN: Jika dia adalah 'admin', langsung loloskan tanpa banyak tanya!
        if (Auth::user()->role === 'admin') {
            return $next($request);
        }

        // 3. TENDANGAN MAUT: Jika bukan admin, dan role tidak sesuai dengan rute
        if (Auth::user()->role !== $role) {
            // Ini yang akan melempar mereka kembali ke dashboard beserta pesan error kaku
            return redirect()->route('dashboard')->with('error', 'AKSES DITOLAK! OTORITAS ANDA TIDAK MENCUKUPI UNTUK MEMBUKA HALAMAN INI.');
        }

        return $next($request);
    }
}
