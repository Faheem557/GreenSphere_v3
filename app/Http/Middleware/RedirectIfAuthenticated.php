<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect based on user role
                $user = Auth::guard($guard)->user();
                
                if ($user->hasRole('seller')) {
                    return redirect()->route('seller.dashboard');
                } elseif ($user->hasRole('user')) {
                    return redirect()->route('user.dashboard');
                }
            }
        }

        return $next($request);
    }
} 