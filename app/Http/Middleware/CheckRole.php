<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            // No está autenticado
            return redirect('/login');
        }

        $user = Auth::user();

        if (!$user->hasRole($role)) {
            // No tiene el rol requerido
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }

}
