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

        // Normalisasi: admin_tu dianggap sama dengan admin
        $userRole = getUserRole();

        if (!in_array($userRole, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
