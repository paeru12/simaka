<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!in_array(strtolower(Auth::user()->jabatan->jabatan), $roles)) {
            abort(404, 'Page not found');
        }

        return $next($request);
    }
}
?>
