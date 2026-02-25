<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Jika role user saat ini tidak sama dengan role yang diizinkan
        if ($request->user()->role !== $role) {

            // Alihkan kembali ke dashboard dengan pesan error
            return redirect()->route('dashboard')->with('error', 'Akses ditolak! Anda tidak memiliki izin untuk masuk ke menu tersebut.');
        }

        return $next($request);
    }
}
