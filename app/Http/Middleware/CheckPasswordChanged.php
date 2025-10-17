<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckPasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('partner')->user();
        Log::info('Middleware ejecutado para socio: ' . optional($user)->id);

        if ($user && !$user->password_changed && !$request->routeIs('partner.password.change', 'partner.contrasena.cambiada')) {
            return redirect()->route('partner.password.change');
        }

        return $next($request);
    }
}
