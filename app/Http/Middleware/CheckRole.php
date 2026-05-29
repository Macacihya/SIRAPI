<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Membatasi akses route berdasarkan role user.
     *
     * Mendukung M:M role:
     *   - Cek apakah user MEMILIKI salah satu dari role yang diizinkan
     *   - User dengan multi-role (guru+walikelas) bisa mengakses fitur keduanya
     *
     * Penggunaan di routes:
     *   Route::middleware('role:admin')->group(...)
     *   Route::middleware('role:admin,guru')->group(...)
     *
     * @param string ...$roles Role yang diizinkan (admin, guru, walikelas)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Cek apakah user memiliki SALAH SATU dari role yang diizinkan (M:M check)
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
