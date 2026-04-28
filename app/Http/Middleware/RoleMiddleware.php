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
        // 1. Cek login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Cek role (tambahkan strtolower untuk jaga-jaga)
        if (strtolower(Auth::user()->role) !== strtolower($role)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        // Jika sampai sini, artinya aman. Dia akan lanjut ke Controller.
        return $next($request);
    }
}