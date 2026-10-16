<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckPasswordChangeNotification
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->needs_password_change) {
            if (!$request->routeIs(['user.password', 'user-password.update', 'logout'])) {
                return redirect()->route('user.password');
            }
        }

        return $next($request);
    }
}
