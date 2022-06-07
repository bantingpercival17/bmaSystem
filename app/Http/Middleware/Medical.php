<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Administrative
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $_status = false;
        foreach (Auth::user()->roles as $key => $role) {
            if ($role->id == 12) {
                $_status = true;
            }
        }
        return $_status ? $next($request) : redirect('/');
    }
}
