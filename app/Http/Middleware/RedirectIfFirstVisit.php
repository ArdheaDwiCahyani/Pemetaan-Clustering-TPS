<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfFirstVisit
{

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() && !$request->routeIs('login.form', 'register.form', 'forgot-pw', 'reset.pw', 'register')) {
            return redirect()->route('login.form');
        }
        
        return $next($request);
    }
}
