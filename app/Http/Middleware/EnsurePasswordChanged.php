<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password) {
            $changeRoute = match ($user->role) {
                'administrador' => 'admin.change-password',
                'docente'       => 'docente.change-password',
                'alumno'        => 'alumno.change-password',
                default         => null,
            };

            if ($changeRoute && ! $request->routeIs($changeRoute . '*') && ! $request->routeIs('logout')) {
                return redirect()->route($changeRoute);
            }
        }

        return $next($request);
    }
}
