<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SetActiveRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            // Si no hay rol activo, establecer el primero disponible
            if (!$user->getActiveRole()) {
                $firstRole = $user->roles()->first();
                if ($firstRole) {
                    $user->setActiveRole($firstRole->tipo_rol);
                }
            }
        }

        return $next($request);
    }
}
