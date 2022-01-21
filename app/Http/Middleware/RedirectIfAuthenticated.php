<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        /* $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request); */

        $redirectTo = "";
        if (Auth::guard($guards)->check()) {
            $_auth = Auth::user()->roles[0]['id'];
            $redirectTo = $_auth == 1 ? '/administrator' : '';
            $redirectTo = $_auth == 2 ? '/administrative' : $redirectTo;
            $redirectTo = $_auth == 3 ? '/registrar' : $redirectTo;
            $redirectTo = $_auth == 4 ? '/accounting' : $redirectTo;
            $redirectTo = $_auth == 5 ? '/onboard' : $redirectTo;
            $redirectTo = $_auth == 6 ? '/teacher' : $redirectTo;
            $redirectTo = $_auth == 7 ? '/maintenance' : $redirectTo;
            $redirectTo = $_auth == 8 ? '/executive' : $redirectTo;
            $redirectTo = $_auth == 9 ? '/deparment-head' : $redirectTo;
            $redirectTo = $_auth == 10 ? '/dean' : $redirectTo;
            $redirectTo = $_auth == 11 ? '/librarian' : $redirectTo;
            return redirect($redirectTo);
        }
        return $next($request);
    }
}
